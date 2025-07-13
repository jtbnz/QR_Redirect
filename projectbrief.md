# QR Redirect Project Brief

## Project Overview
A PHP web application designed as a landing page for incidents, accessed via QR codes. The system provides secure access control and prevents indexing by search engines and LLMs.

## Core Requirements

### Security & Access Control
- **QR Code Authentication**: Landing page requires a secret key parameter (`?key=SECRET`) for access
- **Admin Authentication**: Separate admin panel with secret key authentication
- **No Search Engine Indexing**: Implemented via robots.txt and meta tags
- **Configuration Security**: Sensitive configuration stored in gitignored config.php file

### Functional Requirements

#### Public Landing Page (index.php)
- Accessible only with valid QR secret key
- Displays list of active incidents
- Auto-redirects to incident URL if only one active incident exists
- Shows "No Current Incidents" when no active incidents
- Clean, minimal interface optimized for mobile devices

#### Admin Panel
- **Login Page** (`admin/login.php`): Secret key authentication
- **Dashboard** (`admin/dashboard.php`): Full incident management
  - Create new incidents (title, URL, status)
  - Toggle incident status (active/inactive)
  - Delete incidents
  - View all incidents in a table format
  - Display QR code URL for easy sharing

#### Data Storage
- JSON file-based storage (`data/incidents.json`)
- No database required
- Automatic file creation and directory management
- CRUD operations handled by IncidentStorage class

## Technical Implementation

### Technology Stack
- **Backend**: PHP (no framework)
- **Frontend**: HTML, CSS, JavaScript (vanilla)
- **Storage**: JSON files
- **Styling**: Custom CSS with black/white/grey color scheme

### File Structure
```
QR_Redirect/
├── index.php                 # Main landing page
├── robots.txt               # Search engine blocking
├── config.php               # Configuration (gitignored)
├── config.example.php       # Configuration template
├── .gitignore              # Git ignore rules
├── admin/
│   ├── login.php           # Admin login page
│   └── dashboard.php       # Admin dashboard
├── includes/
│   ├── bootstrap.php       # Common initialization
│   └── IncidentStorage.php # Data storage class
├── css/
│   └── style.css          # Shared styles
├── data/                  # JSON storage (gitignored)
└── js/                    # JavaScript files (if needed)
```

### Design Specifications
- **Color Palette**: 
  - Primary: Black (#000)
  - Secondary: White (#fff)
  - Accents: Various greys (#111, #333, #666, #ccc)
- **Typography**: System fonts for optimal performance
- **Layout**: Responsive, mobile-first design
- **UI Elements**: Minimal, clean interface with subtle hover effects

### Security Measures
1. No database credentials to manage
2. Secret keys stored in configuration file
3. Configuration file excluded from version control
4. robots.txt blocks all major search engines and AI crawlers
5. Meta tags prevent indexing on all pages
6. Access control on both public and admin sections

### Deployment Considerations
1. Copy `config.example.php` to `config.php`
2. Update secret keys in `config.php`
3. Ensure `data/` directory is writable by web server
4. Configure web server to prevent direct access to includes/ and data/
5. Set up HTTPS for production use

## Future Enhancements (Not Implemented)
- Multiple admin users with individual credentials
- Incident categories or tags
- Scheduled activation/deactivation of incidents
- Analytics tracking for incident clicks
- QR code generation within the admin panel
- Incident expiration dates
- Email notifications for incident changes