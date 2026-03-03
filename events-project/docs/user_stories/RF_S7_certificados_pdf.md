# Estória de Usuário: RF_S7 - Geração de Certificados em PDF

## Estória (Formato Gherkin)

> **Como** participante de um evento,  
> **Quero** baixar meu certificado em PDF após a confirmação da presença,  
> **Para** comprovar minha participação ou apresentação de trabalho.

---

## Especificação do Requisito

### Tipos de Certificado

| Tipo | Condição de Liberação |
|------|----------------------|
| **Participação** | Presença confirmada no evento pelo organizador |
| **Apresentação de Trabalho** | Organizador confirma que o autor apresentou |

### Informações no Certificado

| Campo | Participação | Apresentação |
|-------|--------------|--------------|
| Nome do Participante | ✓ | ✓ |
| CPF (parcial) | ✓ | ✓ |
| Nome do Evento | ✓ | ✓ |
| Data do Evento | ✓ | ✓ |
| Local | ✓ | ✓ |
| Carga Horária | ✓ | ✓ |
| Título do Trabalho | ✗ | ✓ |
| Código de Autenticação | ✓ | ✓ |

---

## Regras de Negócio

| ID | Regra |
|----|-------|
| RN01 | O certificado só pode ser baixado se a condição de liberação for atendida |
| RN02 | O código de autenticação deve ser único e verificável |
| RN03 | O nome do participante deve vir do cadastro (campo `name` do User) |
| RN04 | O layout deve ser paisagem (horizontal) com design profissional |

---

## Critérios de Aceite

```gherkin
Funcionalidade: Geração de Certificados em PDF

  Cenário: Participante baixa certificado de participação
    Dado que o participante teve sua presença confirmada
    Quando ele acessar o dashboard
    Então deve ver o botão "Baixar Certificado"
    E ao clicar, o sistema deve gerar um PDF com os dados corretos

  Cenário: Participante tenta baixar sem presença confirmada
    Dado que o participante NÃO teve presença confirmada
    Quando ele acessar o dashboard
    Então NÃO deve ver o botão de certificado de participação

  Cenário: Autor baixa certificado de apresentação
    Dado que o organizador confirmou a apresentação do trabalho
    Quando o autor acessar o dashboard
    Então deve ver o botão "Certificado de Apresentação"
```

---

## Dependências Técnicas

| Componente | Tecnologia |
|------------|------------|
| Geração de PDF | Laravel DomPDF |
| Template | Blade view (HTML/CSS) |

---

## Layout do Certificado

Design moderno com:
- Bordas decorativas sutis
- Logo do sistema (placeholder)
- Texto centralizado
- Código de autenticação no rodapé
- Orientação paisagem
