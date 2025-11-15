
---

## 🗄️ Banco de Dados (MySQL)

Principais tabelas utilizadas:

- **usuarios**
  - `id`, `nome`, `email`, `senha`, `criado_em`
- **produtos**
  - `id`, `nome`, `descricao`, `preco`, `estoque`, `imagem`
- **pedidos**
  - `id`, `usuario_id`, `status`, `total`, `criado_em`
- **itens_pedido**
  - `id`, `pedido_id`, `produto_id`, `quantidade`, `subtotal`

---

## 💡 Tecnologias Utilizadas

| Tecnologia | Descrição |
|-------------|------------|
| **PHP 8+** | Backend e lógica do sistema |
| **MySQL** | Banco de dados relacional |
| **HTML5 / CSS3 / JavaScript** | Estrutura e estilo |
| **Google Fonts (Poppins)** | Tipografia moderna |
| **Chart.js (opcional)** | Gráficos no painel administrativo |

---

## 🧠 Como Executar o Projeto Localmente

### 🔧 Requisitos:
- PHP 8+
- MySQL
- Servidor local (XAMPP, WAMP, Laragon, etc.)
