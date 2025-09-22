# MKW Project Development Guidelines

## Build and Configuration Instructions

### Prerequisites
- PHP 8.1 or higher
- MySQL/MariaDB database
- Composer for dependency management
- Web server (Apache/Nginx)

### Initial Setup

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Configuration Setup**
   - Copy `config.example.ini` to `config.ini`
   - Configure database connection in `config.ini`:
     ```ini
     db.driver = pdo_mysql
     db.host = your_host
     db.port = 3306
     db.username = your_username
     db.password = your_password
     db.dbname = your_database
     ```
   - Set up paths for templates and uploads
   - Configure WooCommerce integration if needed (wc.key, wc.secret, wc.url)
   - Set up email configuration for SMTP

3. **Database Schema Setup**
   ```bash
   # Generate Doctrine proxy classes
   ./generateproxies.sh
   
   # Update database schema
   ./updateschema.sh
   ```

4. **File Permissions**
   - Ensure `tpl/template_c/`, `tpl/admin/cache/`, and `storage/` directories are writable
   - Set appropriate permissions for upload directories (`kepek/`, etc.)

### Development Mode
Set `developer = 1` in `config.ini` to enable:
- Auto-generation of Doctrine proxy classes
- Enhanced error reporting
- SQL query logging (if `sqllog = 1`)

## Testing Information

### Testing Approach
The project doesn't use a formal testing framework like PHPUnit. Instead, it follows a custom testing approach using simple PHP scripts.

### Running Tests
A simple test example is available that demonstrates testing core functionality:

```bash
php test_example.php
```

This test verifies:
- Entity Manager initialization
- Basic entity operations (Termek entity)
- Configuration loading

### Creating New Tests
To create new tests, follow this pattern:

1. **Create a test file** (e.g., `test_my_feature.php`)
2. **Include bootstrap**: `require_once 'bootstrap.php';`
3. **Use the SimpleTest class pattern** from the example
4. **Test key functionality**:
   - Entity operations
   - Controller methods
   - Business logic
   - Integration points

### Test Example Structure
```php
<?php
require_once 'bootstrap.php';
use Entities\YourEntity;
use mkw\store;

class YourFeatureTest {
    private $passed = 0;
    private $failed = 0;
    
    public function assertEquals($expected, $actual, $message = '') {
        // Implementation from test_example.php
    }
    
    public function testYourFeature() {
        // Your test logic here
    }
    
    public function run() {
        // Run tests and report results
    }
}

$test = new YourFeatureTest();
$test->run();
```

## Development Information

### Architecture Overview
- **Framework**: Custom MVC framework built on Doctrine ORM
- **Template Engine**: Smarty 4.x
- **ORM**: Doctrine ORM 2.6+ with Gedmo extensions
- **Routing**: AltoRouter for URL routing
- **Caching**: Configurable (APC, File, or Array-based)

### Code Organization

#### Entity Structure
- **Location**: `Entities/` directory
- **Pattern**: Doctrine ORM entities with annotations
- **Extensions**: Uses Gedmo extensions for timestamps, slugs, translations
- **Example**: `Entities/Termek.php` for product management

#### Controller Structure
- **Base Class**: All controllers extend `mkwhelpers\MattableController`
- **Pattern**: Standard CRUD operations with custom business logic
- **Location**: `Controllers/` directory
- **Naming**: `{entity}Controller.php` (e.g., `termekController.php`)

#### Key Controller Methods
- `loadVars($t, $forKarb)` - Load template variables
- `setFields($obj)` - Set entity fields from form data
- `afterSave($o, $parancs)` - Post-save operations
- `getlistbody()` - Generate list view data

### Database Management

#### Schema Updates
```bash
# Update schema from entity annotations
./updateschema.sh

# Alternative: Update SQL directly
./updatesql.sh
```

#### Entity Relationships
- Uses Doctrine annotations for relationships
- Supports multilingual content via Gedmo Translatable
- Automatic timestamps via Gedmo Timestampable
- User tracking via Gedmo Blameable

### Configuration Management

#### Cache Configuration
```ini
# Options: apc, file, or leave empty for array cache
cache = file
```

#### Multilingual Support
- Automatic detection via `mkw\store::isMultilang()`
- Default locale: `hu_hu`
- Fallback support enabled
- Translation listener handles locale switching

### Integration Points

#### WooCommerce Integration
- Uses Automattic WooCommerce REST API client
- Product synchronization via `uploadToWc()` methods
- Configuration in `config.ini` under `wc.*` keys

#### Email System
- PHPMailer 6.x for email sending
- SMTP configuration in `config.ini`
- Error reporting via email notifications

### Custom Extensions

#### Custom Doctrine Functions
- `YEAR()` - Extract year from date
- `NOW()` - Current timestamp
- `IF()` - Conditional expressions  
- `RAND()` - Random values
- `CURDATE()` - Current date

#### Event Listeners
- Located in `Listeners/` directory
- Handle business logic on entity lifecycle events
- Examples: `BizonylatfejListener`, `TermekListener`

### File Structure Conventions
- **Templates**: `tpl/admin/default/` for admin, `tpl/main/{theme}/` for frontend
- **JavaScript**: `js/admin/default/` and `js/main/{theme}/`
- **Images**: Organized by type (`kepek/termek/`, `kepek/kategoria/`)
- **Exports**: Various export templates in `exporttemplates/`

### Development Workflow

1. **Create Entity** with Doctrine annotations
2. **Generate Controller** extending MattableController
3. **Update Database Schema** using `./updateschema.sh`
4. **Create Templates** in appropriate theme directory
5. **Add Routes** in routing configuration
6. **Test Functionality** using custom test scripts

### Debugging Tools
- SQL logging via `mkwhelpers\FileSQLLogger`
- Error logging to `onerror.email` if configured
- Developer mode for enhanced debugging

### Performance Considerations
- Use caching (APC recommended for production)
- Proxy class generation should be disabled in production
- SQL query logging should be disabled in production
- Consider using file-based caching for metadata in high-traffic scenarios

---
*Generated on 2025-09-22 for advanced developers working on the MKW project*