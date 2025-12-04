# Inventory & Material Management System

A comprehensive PHP-based ERP module for managing inventory, procurement (inward), material issuance (outward), and shortage tracking with real-time stock calculations.

## 📋 Project Overview

This is a **portfolio project** demonstrating ERP inventory management capabilities. Built during my professional work, this system handles complete material lifecycle management with role-based access control.

**Note:** This is a sanitized version for portfolio purposes. All company-specific branding, data, and configurations have been removed/anonymized.

## ✨ Features

### Core Modules
- **Material Master Management**
  - Hierarchical structure: Category → Type → Subtype
  - Supplier and Vendor management
  - Units master

- **Inward Management (Procurement)**
  - Receive materials from suppliers
  - Bill/Challan tracking
  - Automatic stock updates

- **Outward Management (Issuance)**
  - Issue materials to vendors/users
  - Available quantity validation
  - Purpose tracking

- **Stock Management**
  - Real-time stock calculation: `SUM(Inward) - SUM(Outward)`
  - Shortage alerts based on minimum thresholds
  - Live availability display

- **Request-to-Issue Workflow**
  - Users raise material requests
  - Admin approval process
  - Fulfillment tracking

- **Dashboard & Analytics**
  - Today's inward/outward KPIs
  - 7-day trend charts (Chart.js)
  - Shortage count
  - Pie/Bar visualizations

- **Comprehensive Reporting**
  - Supplier-wise reports
  - Vendor-wise reports
  - Category-wise reports
  - Date-range filtering
  - PDF export (TCPDF)
  - CSV/Excel downloads

- **Access Control**
  - Role-based: Admin vs User
  - Session management
  - Secure authentication

## 🛠️ Tech Stack

- **Backend:** PHP (Procedural)
- **Database:** MySQL (mysqli)
- **Frontend:** Bootstrap 4, jQuery 3.6, HTML5/CSS3
- **Charts:** Chart.js, Chartist
- **PDF Generation:** TCPDF
- **Icons:** Font Awesome
- **Development:** XAMPP/LAMP

## 📁 Project Structure
```
inventory-management-system/
├── admin/              # Main application directory
│   ├── index.php      # Login page
│   ├── dashboard.php  # Main dashboard
│   ├── inward-*.php   # Inward module
│   ├── outward-*.php  # Outward module
│   ├── report-*.php   # Reporting module
│   ├── includes/      # Shared components
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   ├── footer.php
│   │   ├── dbconnection.php
│   │   └── functions.php
│   └── TCPDF-main/    # PDF library
├── database/
│   └── schema.sql     # Database structure
├── .gitignore
└── README.md
```

## 🚀 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP (for local development)

### Setup Steps

1. **Clone the repository**
```bash
   git clone https://github.com/yourusername/inventory-management-system.git
   cd inventory-management-system
```

2. **Database Setup**
```sql
   -- Create database
   CREATE DATABASE inventory_system;
   
   -- Import schema
   mysql -u root -p inventory_system < database/schema.sql
```

3. **Configure Database Connection**
   
   Update `admin/includes/dbconnection.php`:
```php
   $servername = "localhost";
   $username = "root";
   $password = "your_password";
   $dbname = "inventory_system";
```

4. **Set Permissions** (Linux/Mac)
```bash
   chmod 755 -R admin/
   chmod 777 admin/uploads/  # If you have uploads
```

5. **Access Application**
```
   http://localhost/inventory-management-system/admin/
```

6. **Default Login** (Update after first login!)
```
   Username: admin
   Password: admin123
```

## 📊 Database Schema

Key tables:
- `admintbl` - Users and roles
- `tblcat`, `tbltype`, `tblsubtype` - Material hierarchy
- `tblsupplier`, `tblvendor` - Business partners
- `tblinward` - Procurement transactions
- `tbloutward` - Issuance transactions
- `tblreq` - Material requests
- `unit` - Units of measurement

## 🎯 Key Features Explained

### Real-time Stock Calculation
```php
// Event-sourced approach
function getLiveStock($con, $type, $subtype) {
    $inward = "SELECT SUM(quantity) FROM tblinward WHERE type='$type' AND subtype='$subtype'";
    $outward = "SELECT SUM(quantity) FROM tbloutward WHERE type='$type' AND subtype='$subtype'";
    
    return $inwardTotal - $outwardTotal;
}
```

### Shortage Detection
- Compares live stock with minimum threshold
- Dashboard alert count
- Detailed shortage report

## 📸 Screenshots

[Add screenshots here after anonymizing]

## 🔐 Security Notes

- Session-based authentication
- Input sanitization using `mysqli_real_escape_string`
- Role-based access control
- **For production:** Implement prepared statements, HTTPS, CSRF tokens

## 🚧 Known Limitations & Future Improvements

- [ ] Migrate to prepared statements (PDO)
- [ ] Implement MVC architecture or Laravel
- [ ] Add API layer for mobile integration
- [ ] Barcode scanning support
- [ ] Email notifications for low stock
- [ ] Advanced analytics and forecasting

## 📄 License

This project is for **portfolio and educational purposes only**.

**Disclaimer:** This is a sanitized version of a professional project, modified for demonstration purposes. No proprietary business logic or sensitive company information is included.

## 👤 Author

**[Your Name]**
- LinkedIn: [Your Profile]
- Email: your.email@example.com
- Portfolio: [Your Website]

## 🙏 Acknowledgments

Built during professional work experience to demonstrate ERP module development capabilities.

---

**Note to Recruiters:** This project showcases my ability to build complete ERP modules with complex business logic, real-time calculations, and comprehensive reporting. Happy to discuss the architecture and implementation in detail during interviews.
