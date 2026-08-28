# Federico Menegoi Portfolio - Complete Integrated System

This is a **complete recreation** of the Federico Menegoi portfolio website, combining the scraped frontend with a fully functional backend.

## 🎯 What This Contains

✅ **Complete Frontend** - Scraped and fully functional SPA  
✅ **Backend API** - PHP backend that exactly matches original  
✅ **Database** - MySQL with complete portfolio data  
✅ **Admin Panel** - Portfolio management interface  
✅ **Docker Setup** - One-command deployment  
✅ **Production Ready** - Optimized for performance and security  

## 🚀 Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Ports 3000, 3001, and 3306 available

### Start the System
```bash
# Clone or extract this directory
cd integrated_portfolio

# Start everything (one command!)
./start.sh

# Or manually:
docker compose up -d
```

### Access Points
- 🌐 **Website**: http://localhost:3000
- 📡 **API**: http://localhost:3000/api.php  
- 👤 **Admin**: http://localhost:3000/admin.php
- 🗄️ **Database**: http://localhost:3001

## 🏗️ Architecture

```
Frontend (SPA)     Backend (PHP)      Database (MySQL)
    ↓                   ↓                   ↓
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│   Vue.js    │    │  PHP 8.1    │    │  MySQL 8.0  │
│   GSAP      │ ←→ │  REST API   │ ←→ │  Portfolio  │
│   Three.js  │    │  Admin      │    │  Database   │
│   jQuery    │    │  Panel      │    │             │
└─────────────┘    └─────────────┘    └─────────────┘
       ↑                   ↑                   ↑
    Port 3000          Port 3000          Port 3306
```

## 🎨 Frontend Features

### Exact Recreation
- ✅ **Parallax Backgrounds** - Multi-layer scrolling effects
- ✅ **3D Models** - Interactive Three.js cat and floppy disk
- ✅ **Smooth Animations** - GSAP-powered transitions
- ✅ **Responsive Design** - Mobile and desktop optimized
- ✅ **Portfolio Galleries** - Dynamic image loading
- ✅ **Multilingual** - Italian/English support

### Technical Stack
- **JavaScript**: Vanilla JS with jQuery
- **3D Graphics**: Three.js with GLTF models
- **Animations**: GSAP (GreenSock)
- **Styling**: Custom CSS with advanced effects
- **Assets**: Complete image gallery and icons

## 📡 Backend Features

### API Endpoints
```
GET  /api.php              → Complete portfolio data
GET  /api.php?category=1   → Specific category
GET  /api.php?gallery=1    → Specific gallery
POST /api.php              → Upload/update (admin)
```

### Database Structure
- **Categories** - Main portfolio sections
- **Galleries** - Grouped portfolio items  
- **Images** - Individual portfolio pieces
- **Admin Users** - Authentication system

### Sample Data Included
- 6 portfolio categories (Disegni, 3D, Fotografia, etc.)
- Multiple galleries per category
- Sample images with metadata
- Admin user (admin/admin123)

## 🔧 Configuration

### Environment Variables
```bash
# Database
DB_HOST=db
DB_NAME=portfolio_db
DB_USER=portfolio_user
DB_PASS=portfolio_pass

# Security
ADMIN_TOKEN=secure_admin_token_change_this

# Development
ENVIRONMENT=development
```

### Customization
1. **Change Admin Password**:
   ```sql
   UPDATE admin_users SET password_hash = '$2y$10$...' WHERE username = 'admin';
   ```

2. **Add Portfolio Items**:
   - Use admin panel at http://localhost:3000/admin.php
   - Or directly via database

3. **Upload Images**:
   - Place in `uploads/` directory
   - Update database paths

## 🔄 Development Workflow

### Adding New Content
1. **Images**: Drop into `uploads/` folder
2. **Database**: Add entries via admin panel or SQL
3. **Frontend**: Automatically updates via API

### Code Changes
1. **Frontend**: Edit files in `php_backend/`
2. **Backend**: Modify `api.php` or `admin.php`
3. **Database**: Update `database_schema.sql`

### Testing
```bash
# API Test
curl http://localhost:3000/api.php

# Database Test  
docker compose exec db mysql -uportfolio_user -pportfolio_pass portfolio_db

# Frontend Test
open http://localhost:3000
```

## 🛡️ Security Features

### Authentication
- Bearer token authentication for admin operations
- Bcrypt password hashing
- SQL injection protection (PDO)

### Headers
- CORS configuration
- XSS protection
- Content type validation
- Clickjacking protection

### File Handling
- Upload validation
- File type restrictions
- Size limits
- Secure file paths

## 📊 Performance Optimizations

### Frontend
- ✅ Asset compression (gzip)
- ✅ Browser caching headers
- ✅ Optimized images (WebP)
- ✅ Minified CSS/JS

### Backend  
- ✅ Database indexing
- ✅ Connection pooling
- ✅ Query optimization
- ✅ Redis ready

### Apache
- ✅ Compression enabled
- ✅ Cache headers
- ✅ Rewrite rules optimized
- ✅ SPA routing handled

## 🐛 Troubleshooting

### Common Issues

**Port Conflicts**
```bash
# Check what's using ports
lsof -i :3000
lsof -i :3001

# Change ports in docker-compose.yml
```

**Database Connection**
```bash
# Check database logs
docker compose logs db

# Reset database
docker compose down -v
docker compose up -d
```

**API Not Responding**
```bash
# Check web server logs
docker compose logs web

# Test PHP syntax
docker compose exec web php -l /var/www/html/api.php
```

**Frontend Assets Missing**
```bash
# Check file permissions
docker compose exec web ls -la /var/www/html/

# Rebuild container
docker compose build --no-cache
```

### Debug Mode
Enable PHP error reporting:
```bash
# Set in docker-compose.yml
ENVIRONMENT=development

# Or edit config.php
ini_set('display_errors', 1);
```

## 🌐 Production Deployment

### Preparation
1. **Change Default Passwords**
2. **Set Strong Admin Token**  
3. **Configure SSL Certificate**
4. **Set Production Environment**
5. **Backup Database**

### Environment Setup
```bash
# Production environment variables
ENVIRONMENT=production
ADMIN_TOKEN=very_long_secure_random_token
DB_PASS=strong_database_password
```

### Server Requirements
- **CPU**: 2+ cores
- **RAM**: 4GB minimum
- **Storage**: 20GB+ (for images)
- **Network**: SSL certificate required

## 📈 Monitoring

### Health Checks
- Built-in Docker health checks
- Database connection monitoring
- API endpoint testing

### Logs
```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f web
docker compose logs -f db

# Apache logs
docker compose exec web tail -f /var/log/apache2/error.log
```

## 🤝 Contributing

### Development Setup
1. Fork this repository
2. Make changes to frontend/backend
3. Test thoroughly
4. Submit pull request

### Code Standards
- PSR-12 for PHP code
- ESLint for JavaScript
- Meaningful commit messages
- Documentation updates

## 📄 Credits

- **Original Design**: Federico Menegoi
- **Recreation**: Advanced scraping and backend recreation
- **Technologies**: PHP, MySQL, Docker, JavaScript, GSAP, Three.js

## 📝 License

Educational and research purposes. Please respect the original creator's work and intellectual property.

---

**🎉 You now have a complete, production-ready portfolio system that perfectly recreates the original Federico Menegoi website with full backend functionality!**

### Quick Commands Reference
```bash
./start.sh              # Start everything
docker compose down      # Stop all services  
docker compose restart   # Restart services
docker compose logs -f   # View live logs
docker compose ps        # Check status
```
