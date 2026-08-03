NOVA MENSAGEM DO FORMULÁRIO DE CONTATO
==========================================

Nome:      {{ $dados['nome'] }} {{ $dados['sobrenome'] ?? '' }}
Email:     {{ $dados['email'] }}
Data:      {{ now()->format('d/m/Y H:i:s') }}

Mensagem:
----------
{{ $dados['mensagem'] }}
----------

Enviado via formulário do site Fire Out
