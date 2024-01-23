# Rijk-Zwaan
Projeto realizado pela DevSkin. 
1. Cotação e Estoque:
- Reserva do estoque por 24h após a cotação; liberar no estoque 
após esse período. 
- Manter item do estoque reservado durante a venda; liberar se a 
transação não ocorrer em até 24h. 
- Desbloquear permissão para pendente aprovação. 
 Representante não pode aprovar o pedido (Order Approved). 

2. Unfinished Order:
- Regra de 24h para pedidos não finalizados e não cancelados; 
excluir da listagem e retornar ao estoque. 

3. Listagem de Preços em Euro:
- Criar nova listagem em euro com taxa de conversão manual. 
- Habilitar clientes específicos para transações em EURO. 

4. Additional Discount:
- Acrescentar descontos (-1%, -2%, -3%, -4%) apenas para itens em 
EURO. 
- Aprovador específico para pedidos em EURO. 

5. Bônus Order:
- Itens não vão para aprovação da gerência; resolver bug. 

6. Filtros:
- Por status (Order Approved, quotation, pending approval). 
- Por produto (validar produtos). 
- Por representante. 
- Por data. 
- Por valor. 
- Correlação de filtros (mais de uma condição ao filtrar). 

7. Order Client Service:
- Adicionar opção de update sem acessar o pedido inteiro (botão 
de ação). 

8. Horário nos Relatórios:
- Ajustar para exibir horário real nos relatórios de pedidos; resolver 
bug. 

9. Enquadramento da Aplicação:
- Esther gravará vídeo para esclarecimento (feito). 

10. Controle de Repositórios:
- Criar conta no GitHub ou GitLab para controle e organização dos 
repositórios. (Time RIJK irá validar com SI). 

11. Reunião Semanal:
- Todas as sextas-feiras às 14h.
