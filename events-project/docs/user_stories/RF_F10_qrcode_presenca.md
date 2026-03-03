# Estória de Usuário: RF_F10 - Controle de Presença via QR Code

## Estória (Formato Gherkin)

> **Como** organizador de um evento,  
> **Quero** confirmar a presença dos participantes escaneando um QR Code,  
> **Para** agilizar o controle de entrada e liberar os certificados automaticamente.

> **Como** participante de um evento,  
> **Quero** ter acesso ao meu QR Code de presença,  
> **Para** apresentá-lo no dia do evento e confirmar minha participação.

---

## Regras de Negócio

| ID | Regra |
|----|-------|
| RN01 | Cada inscrição CONFIRMADA (paga) recebe um QR Code único |
| RN02 | O QR Code só pode ser usado uma vez (uso único) |
| RN03 | O QR Code expira automaticamente após a data do evento |
| RN04 | A leitura do QR marca `attended = true` na inscrição |
| RN05 | QR exibido no dashboard e enviado por e-mail |

---

## Especificação Técnica

### Geração do QR Code
- Conteúdo: URL assinada com token único
- Formato: `{APP_URL}/presenca/{inscription_id}/{token}`
- Token: Hash SHA256 do ID + chave secreta

### Interface de Leitura (Organizador)
- Página com acesso à câmera via HTML5 (API MediaDevices)
- Decodificação com biblioteca JavaScript (html5-qrcode)
- Validação via AJAX ao servidor

---

## Critérios de Aceite

```gherkin
Funcionalidade: Controle de Presença via QR Code

  Cenário: Participante visualiza QR Code
    Dado que o participante tem inscrição confirmada (pago)
    Quando ele acessar o dashboard
    Então deve ver seu QR Code de presença

  Cenário: Organizador escaneia QR Code válido
    Dado que o organizador está na página de leitura de QR
    Quando ele escanear um QR Code de um participante confirmado
    Então o sistema deve marcar a presença automaticamente
    E exibir mensagem de sucesso com nome do participante

  Cenário: QR Code já utilizado
    Dado que o participante já teve presença confirmada
    Quando o organizador tentar escanear o mesmo QR
    Então o sistema deve informar "Presença já confirmada"

  Cenário: QR Code de evento passado
    Dado que a data do evento já passou
    Quando o organizador tentar escanear um QR
    Então o sistema deve informar "QR Code expirado"
```
