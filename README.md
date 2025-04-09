# Student Question Posting System

A CRUD web application designed for students to post coursework-related questions, engage in peer discussions, and collaborate under administrative oversight. Built as part of the COMP1841 Web Programming 1 coursework at the University of Greenwich, this project leverages PHP, MySQL, and modern web technologies to create a student-focused platform inspired by Quora and Stack Overflow.

## Features

- **Public Post List**: View a list of questions/posts with titles, authors, module tags, and comment/vote counts.
- **CRUD Operations**: Users can create, read, update, and delete posts, with support for image uploads (e.g., screenshots).
- **User Management**: Signup and login system, with a ban mechanism toggling user status (active/banned) instead of deletion.
- **Voting System**: Upvote posts and comments to highlight valuable content.
- **Comment System**: Full CRUD for comments under each post, fostering discussion.
- **Module Management**: Admins can add, edit, and delete module names, assignable to posts via dropdowns.
- **Admin Dashboard**: Manage posts, modules, and users, including banning capabilities.
- **Search Functionality**: Quickly retrieve posts by keywords.
- **Accessibility**: Adheres to WCAG 2.1 with semantic HTML and high Lighthouse scores (Accessibility: 95).
- **Security**: Password hashing with PHP’s `password_hash()`, XSS prevention with `htmlspecialchars()`, and session-based authentication.

## Technologies Used

- **Frontend**: HTML5 (semantic tags), Tailwind CSS (responsive design), JavaScript (dynamic features like modals and fetch API).
- **Backend**: PHP 8.x with PDO for secure database interactions, MVC architecture, and custom routing.
- **Database**: MySQL 8.0, designed in 3NF with 8 tables (e.g., `users`, `posts`, `modules`, `postvotes`).
- **Tools**: XAMPP (local development), VS Code, Git.

## Project Structure
```
📂 root/
├── 📂 config/             # Configuration files
│   └── 📄 database.php    # Database connection settings
├── 📂 favicon/            # Favicon assets
├── 📂 public/             # Static files and entry point
│   ├── 📂 css/            # Tailwind CSS styles
│   ├── 📂 js/             # JavaScript (validator.js, script.js)
│   ├── 📄 .htaccess       # URL rewriting and security
│   └── 📄 index.php       # Main entry point
├── 📂 src/                # Core application logic
│   ├── 📂 controllers/    # Input validation and response handling
│   ├── 📂 dal/            # Data Access Layer for DB interactions
│   ├── 📂 models/         # Data Access Objects mapped to DB schema
│   ├── 📂 routes/         # Route definitions
│   ├── 📂 styles/         # Additional styles (if separate from public/css)
│   ├── 📂 utils/          # Router, SessionManager, Template classes
│   └── 📂 views/          # Templates (admin, users, posts, etc.)
├── 📂 uploads/            # Uploaded images
├── 📂 tests/              # Reserved for future test cases
└── 📄 README.md           # Project documentation
```
## Installation

1. **Prerequisites**:
   - XAMPP (with PHP 8.x, MySQL 8.0, and Apache).
   - Composer (for autoloading dependencies).
   - Git installed.

2. **Install Composer**:
   - Download and install Composer from [getcomposer.org](https://getcomposer.org).
   - Run `composer install` in the project root to generate `vendor/autoload.php` for autoloading.

3. **Clone the Repository**:
   ```bash
   git clone https://github.com/phamthang44/coursework-web
