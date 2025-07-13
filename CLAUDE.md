# CLAUDE.md - Project Memory Bank

## Project Overview
This is a QR code redirect system with incident management capabilities. The system is designed to be accessed via QR codes and prevents search engine indexing.

## Key Architecture Decisions

### Authentication Model
- **QR Access**: Uses URL parameter `?key=SECRET` for public access
- **Admin Access**: Single secret key authentication (no username required)
- **Session-based**: Admin sessions expire after 1 hour

### Data Storage
- **JSON-based**: Simple file storage in `data/incidents.json`
- **No Database**: Intentionally avoiding database complexity
- **File Locking**: PHP's file_put_contents handles basic concurrency

### Security Measures
1. Config file (`config.php`) is gitignored
2. robots.txt blocks all crawlers including AI/LLM bots
3. Meta tags on all pages prevent indexing
4. Access control on both public and admin interfaces

## Important URLs and Paths
- **Public Access**: `/index.php?key={qr_secret}`
- **Admin Login**: `/admin/login.php`
- **Admin Dashboard**: `/admin/dashboard.php`
- **Data Storage**: `/data/incidents.json`

## Development Patterns

### Error Handling
- Simple die() statements for critical errors
- User-friendly messages for validation errors
- No detailed error exposure in production

### Code Organization
- `includes/bootstrap.php`: Common initialization
- `includes/IncidentStorage.php`: Data access layer
- Inline CSS for simplicity (with shared `style.css` available)

### Styling Approach
- CSS variables for theming
- Mobile-first responsive design
- Minimalist black/white/grey color scheme
- System fonts for performance

## Common Tasks

### Adding New Incident Fields
1. Update `IncidentStorage::createIncident()` method
2. Update admin dashboard form
3. Update display logic in index.php
4. Consider backward compatibility for existing data

### Changing Authentication
1. Update config structure in `config.example.php`
2. Modify authentication logic in `bootstrap.php`
3. Update login form if needed

### Debugging Tips
- Check `data/incidents.json` directly for data issues
- Verify file permissions on `data/` directory
- Test with `error_reporting(E_ALL)` for development
- Check PHP session configuration if login issues occur

## Known Limitations
1. Single admin user (no multi-user support)
2. No audit trail or logging
3. Basic concurrency handling
4. No built-in backup mechanism
5. Manual QR code generation required

## Future Enhancement Ideas
- Multi-user admin with role-based access
- Incident categories and filtering
- QR code generation in admin panel
- Scheduled incident activation
- API endpoint for external integrations
- Incident analytics and click tracking
- Email notifications
- Backup and restore functionality

## Testing Checklist
- [ ] Public access with correct QR key
- [ ] Public access rejection with wrong/missing key
- [ ] Admin login with correct secret
- [ ] Admin login rejection with wrong secret
- [ ] Create new incident
- [ ] Toggle incident status
- [ ] Delete incident
- [ ] Auto-redirect with single active incident
- [ ] Multiple incident selection
- [ ] "No incidents" display
- [ ] Mobile responsive design
- [ ] Session timeout behavior

## Deployment Notes
1. Always use HTTPS in production
2. Set appropriate file permissions (755 for directories, 644 for files)
3. Configure web server to block direct access to `/includes/` and `/data/`
4. Consider CDN for CSS/JS if scaling needed
5. Monitor disk space for JSON storage growth
6. Regular backups of `data/incidents.json` recommended