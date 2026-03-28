<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Work;
use App\Models\WorkType;
use App\Notifications\WorkSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    /**
     * Mostra o formulário de submissão de trabalho. (RF-F5)
     */
    public function create(Event $event)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Encontrar a inscrição do utilizador logado para este evento
        $inscription = $user->inscriptions()
            ->where('event_id', $event->id)
            ->first();

        // 2. Verificações de segurança
        if (!$inscription) {
            return redirect()->route('dashboard')->with('error', 'Inscrição não encontrada.');
        }
        if (!$inscription->inscriptionType->allow_work_submission) {
            return redirect()->route('dashboard')->with('error', 'O seu tipo de inscrição não permite submissão de trabalhos.');
        }
        if ($inscription->work_id) {
            return redirect()->route('dashboard')->with('error', 'Você já submeteu um trabalho para esta inscrição.');
        }

        // 3. Buscar os tipos de trabalho (ex: Artigo, Resumo) para o dropdown
        $workTypes = WorkType::all(); 

        return view('works.create', [
            'event' => $event,
            'inscription' => $inscription,
            'workTypes' => $workTypes,
        ]);
    }

    /**
     * Armazena o trabalho submetido. (RF-F5)
     */
    public function store(Request $request, Event $event)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validar os dados do formulário
        $request->validate([
            'title' => 'required|string|max:255',
            'work_type_id' => 'required|exists:work_types,id',
            'advisor' => 'required|string|max:255',
            'co_authors_text' => 'nullable|string|max:255',
            'abstract' => 'required|string|min:100', // Resumo
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB Max
        ]);

        // 2. Encontrar a inscrição (verificação de segurança)
        $inscription = $user->inscriptions()
            ->where('event_id', $event->id)
            ->firstOrFail();
        
        if ($inscription->work_id) {
            return redirect()->route('dashboard')->with('error', 'Trabalho já submetido.');
        }

        // 3. Upload Absoluto (Move fisicamente o arquivo para a pasta public)
        if (!$request->hasFile('file')) {
            return back()->with('error', 'O arquivo do trabalho não foi recebido pelo servidor.');
        }

        $file = $request->file('file');
        
        // Cria um nome único com a extensão original
        $fileName = time() . '_' . $file->getClientOriginalName();
        
        // Move o arquivo FISICAMENTE para a pasta public/uploads/works
        $file->move(public_path('uploads/works'), $fileName);
        
        // Salva o caminho exato no banco de dados
        $filePath = 'uploads/works/' . $fileName;

        // 4. Usar uma Transação para garantir que o banco de dados é salvo
        try {
            DB::beginTransaction();

            // 4a. Criar o registo do Trabalho
            $work = Work::create([
                'user_id' => $user->id,
                'work_type_id' => $request->work_type_id,
                'title' => $request->title,
                'abstract' => $request->abstract,
                'advisor' => $request->advisor,
                'co_authors_text' => $request->co_authors_text,
                'file_path' => $filePath,
            ]);

            // 4b. Ligar o Trabalho à Inscrição
            $inscription->work_id = $work->id;
            $inscription->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Só apaga o arquivo físico se der erro crítico no BANCO DE DADOS
            $fullPath = public_path($filePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            return back()->with('error', 'Erro ao salvar os dados no banco. Tente novamente.');
        }

        // 5. Notificação ISOLADA (Se o Mailtrap der erro 550, o arquivo NÃO será mais apagado)
        try {
            $user->notify(new WorkSubmittedNotification($work));
        } catch (\Exception $e) {
            // Ignora o erro de limite de e-mail silenciosamente e segue a vida
        }

        // 6. Redirecionar
        return redirect()->route('dashboard')->with('success', 'Trabalho submetido com sucesso!');
    }

    /**
     * Download do trabalho (RF_A6, RF_S3)
     */
    public function download(\App\Models\Work $work)
    {
        // Conserta o bug das barras invertidas do Windows para o PHP achar o arquivo fisicamente
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, public_path($work->file_path));
        
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        abort(404, 'Erro crítico: O arquivo não foi encontrado no caminho físico: ' . $path);
    }
}