# 🎓 Smart Edu - Learning Management System

<div align="center">

![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

A comprehensive and modern Learning Management System built with Laravel 12, featuring role-based access control, course management, enrollment tracking, and an intuitive dashboard for students and instructors.

![Smart Edu LMS](https://via.placeholder.com/800x400?text=Smart+Edu+LMS+Dashboard)

</div>

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Modern Login UI** - Glassmorphism design with two-sided layout
- **Role-Based Access Control** - Admin, Instructor, and Student roles
- **Secure Authentication** - Laravel Breeze with email verification
- **Permission Management** - Spatie Laravel Permission integration

### 📚 Course Management
- **Full CRUD Operations** - Create, read, update, and delete courses
- **Advanced Search** - Filter by level, status, price range, and keywords
- **Thumbnail Upload** - Image management with Laravel Storage
- **Course Statistics** - Track modules, lessons, and enrollments

### 🎯 Enrollment System
- **Progress Tracking** - Visual progress bars and percentage completion
- **Enrollment History** - Track all enrolled courses
- **Course Completion** - Mark courses as complete
- **Unenrollment** - Easy course withdrawal

### 📊 Dashboard
- **Role-Based Dashboards** - Customized views for each role
- **Interactive Statistics** - Cards with hover effects
- **Chart Analytics** - Visual data representation with Chart.js
- **Real-Time Updates** - Dynamic statistics

### 🎨 UI/UX
- **Responsive Design** - Mobile-first approach
- **Modern UI** - Glassmorphism and gradient effects
- **Bottom Navigation** - Mobile-friendly navigation
- **Smooth Animations** - Enhanced user experience

### 📱 Mobile Features
- **Bottom Navigation** - Glassmorphism mobile navigation
- **Responsive Grids** - Uniform card layouts
- **Touch-Optimized** - Mobile-friendly interactions
- **Adaptive Design** - Works on all screen sizes

---

## 🛠 Tech Stack

### Backend
- **Laravel 12** - Modern PHP framework
- **Laravel Breeze** - Authentication scaffolding
- **Spatie Laravel Permission** - Role and permission management
- **Laravel Scout** - Full-text search capabilities
- **SQLite** - Database (easily switchable to MySQL/PostgreSQL)

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Skydash Admin Template** - Professional admin dashboard
- **Chart.js** - Interactive charts and graphs
- **Bootstrap 4** - Responsive grid system

### Tools
- **Composer** - PHP dependency management
- **NPM** - JavaScript package management
- **Laravel Mix** - Asset compilation

---

## 📦 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL)

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/stevencodelab/learning-management-system.git
cd learning-management-system
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install NPM dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Database setup**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run build
# or for development
npm run dev
```

7. **Create storage link**
```bash
php artisan storage:link
```

8. **Serve the application**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

### Default Login Credentials

After seeding, you can login with:
- **Admin**: admin@example.com / password
- **Instructor**: instructor@example.com / password
- **Student**: student@example.com / password

---

## 📸 Screenshots

### Dashboard Overview
<div align="center">
  
![Dashboard](https://via.placeholder.com/800x450?text=Dashboard+Screenshot)
  
*Interactive dashboard with statistics and analytics*

</div>

### Course Management
<div align="center">
  
![Course Management](https://via.placeholder.com/800x450?text=Course+Management+Screenshot)
  
*Create, edit, and manage courses with ease*

</div>

### Enrollment Tracking
<div align="center">
  
![Enrollments](https://via.placeholder.com/800x450?text=Enrollment+Tracking+Screenshot)
  
*Track student progress and completions*

</div>

### Mobile View
<div align="center">
  
![Mobile Navigation](https://via.placeholder.com/400x700?text=Mobile+Navigation)
  
*Responsive mobile design with bottom navigation*

</div>

---

## 🚀 Features in Detail

### For Students
- ✅ Browse and enroll in available courses
- ✅ Track learning progress with visual indicators
- ✅ Access course materials and lessons
- ✅ Monitor enrollment history
- ✅ Complete courses and earn achievements

### For Instructors
- ✅ Create and manage courses with rich content
- ✅ Upload course thumbnails and materials
- ✅ Monitor student enrollments
- ✅ Track course statistics and analytics
- ✅ Manage modules and lessons

### For Admins
- ✅ Complete platform administration
- ✅ Manage users, roles, and permissions
- ✅ Oversee all courses and enrollments
- ✅ View comprehensive analytics and reports
- ✅ Control system-wide settings

---

## 📁 Project Structure

```
learning-management-system/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CourseController.php
│   │       ├── EnrollmentController.php
│   │       ├── DashboardController.php
│   │       └── ...
│   └── Models/
│       ├── Course.php
│       ├── Enrollment.php
│       ├── User.php
│       └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── courses/
│   │   ├── enrollments/
│   │   ├── dashboard/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
└── public/
    └── build/
```

---

## 🔧 Configuration

### Search Configuration
The system uses Laravel Scout for advanced search. Configure it in `config/scout.php`:
```php
'driver' => env('SCOUT_DRIVER', 'collection'),
```

### Storage Configuration
Ensure the storage link is created:
```bash
php artisan storage:link
```

### Permission Setup
Roles and permissions are seeded automatically. Customize in `database/seeders/RolePermissionSeeder.php`.

---

## 🧪 Testing

Run the test suite with:
```bash
php artisan test
```

Or with Pest (if using):
```bash
./vendor/bin/pest
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

<div align="center">

**Steven Morrison**

- GitHub: [@stevencodelab](https://github.com/stevencodelab)
- Instagram: [@_stevenmorison](https://instagram.com/_stevenmorison)
- Email: stevencodelab@gmail.com

</div>

---

## 🌟 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Laravel Breeze](https://laravel.com/docs/breeze) - Lightweight authentication
- [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) - Permission management
- [Skydash](https://www.bootstrapdash.com/product/skydash/) - Admin dashboard template
- [Chart.js](https://www.chartjs.org/) - Beautiful charts
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS

---

<div align="center">

Made with ❤️ by [Steven Morrison](https://github.com/stevencodelab)

⭐ Star this repo if you find it helpful!

</div>