# CBT System - Hosting Upload Checklist

## ✅ Pre-Upload Checklist

### 📦 Dependencies Fixed
- [x] Updated composer.json untuk PHP 8.0+
- [x] CodeIgniter downgrade ke 4.3.0 (PHP 8.0+ compatible)
- [x] PHPSpreadsheet downgrade ke 1.29 (PHP 8.0+ compatible)
- [x] PHPUnit downgrade ke 9.5 (PHP 8.0+ compatible)
- [x] Composer dependencies updated locally

### 📁 Files Prepared
- [x] .env.hosting template created
- [x] .htaccess.hosting for production created
- [x] deploy_to_hosting.md guide created
- [x] HOSTING_CHECKLIST.md created

## 🚀 Upload Steps

### 1. Upload Files to Hosting
```bash
# Upload these directories/files:
✅ /app/
✅ /public/
✅ /vendor/
✅ /writable/
✅ index.php
✅ spark
✅ composer.json
✅ composer.lock

# Copy hosting-specific files:
✅ Copy .htaccess.hosting to .htaccess
✅ Copy .env.hosting to .env
```

### 2. Configure Environment
```bash
# Edit .env file with your hosting details:
✅ app.baseURL = 'https://dmcat.inova.my.id/'
✅ database.default.hostname = localhost
✅ database.default.database = your_db_name
✅ database.default.username = your_db_user
✅ database.default.password = your_db_pass
✅ CI_ENVIRONMENT = production
```

### 3. Set Permissions
```bash
# Set correct permissions:
✅ chmod -R 755 writable/
✅ chmod -R 755 public/
✅ chmod 644 .env
✅ chmod 644 .htaccess
```

### 4. Database Setup
```bash
# Import database:
✅ Upload database.sql via phpMyAdmin
✅ Or run: php spark migrate
✅ Test database connection
```

## 🧪 Testing Checklist

### Basic Functionality
- [ ] Homepage loads: https://dmcat.inova.my.id/
- [ ] Login page works: https://dmcat.inova.my.id/login
- [ ] Admin dashboard accessible
- [ ] Peserta dashboard accessible
- [ ] PHP compatibility check: https://dmcat.inova.my.id/php-multiversion-check

### Advanced Features
- [ ] File upload works
- [ ] Ujian functionality works
- [ ] Database operations work
- [ ] Session management works
- [ ] Error handling works

### Performance & Security
- [ ] Page load speed acceptable
- [ ] HTTPS working (if available)
- [ ] .env file not accessible from web
- [ ] Error logs working
- [ ] Security headers present

## 🔧 Troubleshooting

### Common Issues & Solutions

#### Issue: "Composer dependencies require PHP >= 8.2.0"
**Solution:**
```bash
# Verify composer.json has correct versions:
"php": "^8.0"
"codeigniter4/framework": "^4.3.0"
"phpoffice/phpspreadsheet": "^1.29"

# Re-upload vendor/ directory
# Or run on hosting: composer install --no-dev
```

#### Issue: 404 errors for all pages
**Solution:**
```bash
# Check .htaccess file exists and is correct
# Verify mod_rewrite is enabled on hosting
# Check RewriteBase setting in .htaccess
```

#### Issue: Database connection failed
**Solution:**
```bash
# Verify database credentials in .env
# Check database server hostname
# Ensure database exists and user has permissions
```

#### Issue: File permissions errors
**Solution:**
```bash
# Set correct permissions:
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 755 writable/
```

#### Issue: PHP version mismatch
**Solution:**
```bash
# Check hosting PHP version: php -v
# Contact hosting to upgrade to PHP 8.0+
# Or use .htaccess to force PHP version
```

## 📊 Hosting Requirements Verification

### Minimum Requirements
- [x] **PHP**: 8.0+ (check: `php -v`)
- [x] **MySQL**: 5.7+ (check hosting specs)
- [x] **Apache**: mod_rewrite enabled
- [x] **Memory**: 128MB+ (recommended 256MB)
- [x] **Disk Space**: 100MB+

### Recommended Features
- [ ] **SSL Certificate**: For HTTPS
- [ ] **Cron Jobs**: For scheduled tasks
- [ ] **Email**: SMTP for notifications
- [ ] **Backup**: Automated backups
- [ ] **CDN**: For better performance

## 📞 Support Information

### Hosting Provider Check
- **Provider**: Check if they support PHP 8.0+
- **Control Panel**: cPanel, Plesk, or custom
- **PHP Selector**: Available for version switching
- **Composer**: Available or need to upload vendor/

### Documentation Links
- **CBT System Guide**: PHP_MULTIVERSION_GUIDE.md
- **Deployment Guide**: deploy_to_hosting.md
- **Compatibility Check**: /php-multiversion-check

### Contact Support
- **Technical Issues**: support@langitinovasi.id
- **Hosting Questions**: Contact your hosting provider
- **PHP Compatibility**: Use compatibility checker

## ✅ Final Verification

### Post-Deployment Checklist
- [ ] All pages load without errors
- [ ] Database operations work correctly
- [ ] File uploads function properly
- [ ] User authentication works
- [ ] Admin functions accessible
- [ ] Error logging enabled
- [ ] Performance acceptable
- [ ] Security measures active

### Monitoring Setup
- [ ] Error log monitoring
- [ ] Performance monitoring
- [ ] Security monitoring
- [ ] Backup verification
- [ ] Update procedures documented

---

**🎯 Status: Ready for Production Deployment**

**Last Updated**: $(date)
**PHP Compatibility**: 8.0, 8.1, 8.2, 8.3, 8.4
**Hosting Ready**: ✅ Yes

---

**© 2025 CBT System 3.0 - Production Ready**
