# Task Management 1.0 

A web-based task management system built with PHP and MySQL, developed as a high school technical project. The app allows users to sign in, create, edit, filter and delete their personal tasks.

---

##  Technologies

PHP • MySQL (PDO) • HTML • CSS • Bootstrap 5

---

##  Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) or any local server with PHP and MySQL

### Installation

1. Clone the repository
   ```bash
   git clone https://github.com/your-username/task-management.git
   ```

2. Move the project folder to your server's root directory (e.g., `htdocs` for XAMPP)

3. Create the database and tables in phpMyAdmin:
   ```sql
   CREATE DATABASE taskmanagement;

   USE taskmanagement;

   CREATE TABLE usuarios (
     id INT AUTO_INCREMENT PRIMARY KEY,
     usuario VARCHAR(100) NOT NULL,
     senha VARCHAR(255) NOT NULL
   );

   CREATE TABLE tarefas (
     id INT AUTO_INCREMENT PRIMARY KEY,
     titulo VARCHAR(255) NOT NULL,
     descricao TEXT,
     status VARCHAR(50) NOT NULL,
     usuario_id INT,
     FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
   );
   ```

4. Update database credentials in `login_usuario.php` and `tasks.php` if needed:
   ```php
   $dsn = 'mysql:host=localhost;dbname=taskmanagement';
   $usuariobd = 'root';
   $senhabd = '';
   ```

5. Start Apache and MySQL in XAMPP and access the project at:
   ```
   http://localhost/task-management/
   ```

---

##  Screenshots

<img width="1919" height="1016" alt="image" src="https://github.com/user-attachments/assets/a8b33e10-74a8-4b6a-894e-483e8e2f69bf" />
<img width="1919" height="911" alt="image" src="https://github.com/user-attachments/assets/42d42aff-f0c9-4869-8017-82cbea16a721" />
<img width="1917" height="927" alt="image" src="https://github.com/user-attachments/assets/ab58c369-6610-49e2-a6c5-2d31e3f2e2d8" />




---

##  Author

**Gabriel Passarela**  
[LinkedIn](https://www.linkedin.com/in/gabriel-passarela-70a633326/) • [GitHub](https://github.com/your-username)

---

## 📄 License

This project is for educational purposes.
