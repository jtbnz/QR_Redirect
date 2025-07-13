# QR_Redirect

A secure PHP web application that provides QR code-based access to incident information with dynamic URL redirection. The system prevents search engine indexing and includes a full admin panel for incident management.

## Features

- 🔒 **Secure Access**: QR code authentication for public access
- 🚫 **No Indexing**: Blocks search engines and AI crawlers
- 📱 **Mobile Optimized**: Clean, responsive design
- ⚡ **Auto-Redirect**: Automatically redirects when single incident is active
- 👤 **Admin Panel**: Full incident management capabilities
- 💾 **Simple Storage**: JSON-based data storage (no database required)
- 🎨 **Modern Design**: Minimalist black/white/grey color scheme

## Requirements

- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Write permissions for `data/` directory

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/jtbnz/QR_Redirect.git
   cd QR_Redirect
   ```

2. Copy the configuration template:
   ```bash
   cp config.example.php config.php
   ```

3. Edit `config.php` and update the secret keys:
   ```php
   'qr_secret' => 'your-secure-qr-secret-here',
   'admin_secret' => 'your-secure-admin-secret-here',
   ```

4. Ensure the `data/` directory is writable:
   ```bash
   chmod 755 data/
   ```

5. Configure your web server to point to the project directory

## Usage

### For End Users

Access the landing page using a QR code that points to:
```
https://yourdomain.com/index.php?key=YOUR_QR_SECRET
```

- If there's one active incident, users are automatically redirected
- If there are multiple active incidents, users see a selection list
- If there are no active incidents, users see "No Current Incidents"

### For Administrators

1. Access the admin panel at:
   ```
   https://yourdomain.com/admin/login.php
   ```

2. Login using the admin secret key from your `config.php`

3. From the dashboard, you can:
   - Create new incidents with title, URL, and status
   - Toggle incident status between active and inactive
   - Delete incidents
   - View the QR code URL for sharing

## Security Considerations

- **Always use HTTPS** in production
- **Keep config.php secure** - it contains your authentication secrets
- **Regularly update secrets** - especially if shared widely
- **Monitor access logs** - check for unauthorized access attempts
- Consider adding `.htaccess` rules to protect sensitive directories:
  ```apache
  # Protect includes directory
  <Directory "includes">
      Order deny,allow
      Deny from all
  </Directory>
  
  # Protect data directory
  <Directory "data">
      Order deny,allow
      Deny from all
  </Directory>
  ```

## File Structure

```
QR_Redirect/
├── index.php                 # Main landing page
├── robots.txt               # Search engine blocking
├── config.php               # Configuration (create from example)
├── config.example.php       # Configuration template
├── admin/
│   ├── login.php           # Admin authentication
│   └── dashboard.php       # Incident management
├── includes/
│   ├── bootstrap.php       # Common initialization
│   └── IncidentStorage.php # Data handling class
├── css/
│   └── style.css          # Shared styles
└── data/                  # JSON storage (auto-created)
```

## Customization

### Styling
Edit `css/style.css` to customize the appearance. The CSS uses CSS variables for easy color scheme changes:

```css
:root {
    --color-black: #000;
    --color-white: #fff;
    --color-dark-grey: #111;
    /* ... etc ... */
}
```

### Adding Features
The modular structure makes it easy to extend:
- Add new fields to incidents in `IncidentStorage.php`
- Create new admin pages by extending the authentication check
- Modify the public interface in `index.php`

## Troubleshooting

**"Access denied" on landing page**
- Check that the QR code URL includes the correct `?key=` parameter
- Verify the `qr_secret` in your config.php matches

**"config.php not found" error**
- Copy `config.example.php` to `config.php`
- Update the secret values

**Cannot create or modify incidents**
- Check that the `data/` directory exists and is writable
- Verify PHP has write permissions

**Admin login not working**
- Ensure you're using the `admin_secret` from config.php
- Check that sessions are enabled in PHP

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open source. Please add your preferred license.
