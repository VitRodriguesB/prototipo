# Estória de Usuário: RF_F2 - Melhoria no Upload de Comprovantes

## Estória (Formato Gherkin)

> **Como** participante de um evento,  
> **Quero** enviar meu comprovante de pagamento com feedback visual claro,  
> **Para** ter certeza de que o arquivo foi enviado corretamente.

> **Como** organizador de um evento,  
> **Quero** visualizar e baixar os comprovantes enviados,  
> **Para** validar os pagamentos de forma eficiente.

---

## Especificação do Requisito

### Melhorias para o Participante

| ID | Funcionalidade | Descrição |
|----|----------------|-----------|
| M01 | Preview de Imagem | Ao selecionar um arquivo, exibir prévia da imagem antes de enviar |
| M02 | Barra de Progresso | Mostrar progresso do upload em tempo real |
| M03 | Feedback Visual | Mensagem clara de sucesso ou erro após conclusão |

### Melhorias para o Organizador

| ID | Funcionalidade | Descrição |
|----|----------------|-----------|
| M04 | Visualizar Comprovante | Exibir o comprovante diretamente na tela de análise |
| M05 | Download | Permitir baixar o arquivo para análise offline |

---

## Regras de Negócio

| ID | Regra |
|----|-------|
| RN01 | Formatos aceitos: JPEG, PNG, JPG e PDF |
| RN02 | Tamanho máximo: 2MB |
| RN03 | Apenas o último comprovante é mantido (substitui o anterior) |
| RN04 | Preview de PDF exibe ícone de documento (não a imagem) |

---

## Critérios de Aceite

```gherkin
Funcionalidade: Upload de Comprovante Melhorado

  Cenário: Preview de imagem antes do envio
    Dado que o participante está na tela de pagamento
    Quando ele selecionar um arquivo de imagem
    Então o sistema deve exibir uma prévia da imagem
    E mostrar o nome e tamanho do arquivo

  Cenário: Barra de progresso durante upload
    Dado que o participante selecionou um comprovante
    Quando ele clicar em "Enviar Comprovante"
    Então o sistema deve exibir uma barra de progresso
    E desabilitar o botão de envio até concluir

  Cenário: Organizador visualiza comprovante
    Dado que o organizador está na tela de análise de pagamentos
    Quando ele visualizar uma inscrição pendente
    Então o sistema deve exibir a imagem do comprovante
    E mostrar um botão para download
```

---

## Arquivos a Modificar

| Arquivo | Alteração |
|---------|-----------|
| `resources/views/participant/payment.blade.php` | Adicionar preview e barra de progresso |
| `resources/views/organization/payments/index.blade.php` | Adicionar visualização do comprovante |
| `routes/web.php` | Adicionar rota para download do comprovante |
| `PaymentController.php` | Adicionar método de download |
