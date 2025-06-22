# Webhook Tracker 2.0

A modern Laravel application for tracking and monitoring webhook events in real-time. Built with Laravel 11, Livewire 3, and Flux UI components.

## Features

- 🔄 **Real-time Webhook Tracking** - Monitor webhook events as they happen
- 📊 **Event Dashboard** - View, filter, and search webhook events
- 🌙 **Dark/Light Mode** - Toggle between themes with system preference support
- 📱 **Responsive Design** - Mobile-first approach with modern UI
- 🔍 **Advanced Filtering** - Filter by event type, search content, and more
- 📝 **Detailed Event View** - Inspect headers, payload, and metadata
- ⚡ **Live Updates** - Real-time polling for new events
- 🗂️ **Session Management** - Organize webhooks by sessions

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Alpine.js
- **UI Components**: Flux UI
- **Styling**: Tailwind CSS
- **Database**: SQLite (configurable)
- **Queue**: Database driver
- **Cache**: Database driver

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd WebhookTracker2.0
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## Usage

### Webhook Testing

1. **Access the Dashboard**: Navigate to the application homepage
2. **Get Your Webhook URL**: Copy the generated webhook URL from the dashboard
3. **Send Test Webhooks**: Use the provided URL in your webhook configurations
4. **Monitor Events**: View incoming webhooks in real-time

### Supported HTTP Methods

- GET
- POST
- PUT
- DELETE
- PATCH
- HEAD
- OPTIONS

### Event Information Captured

- HTTP Method
- Headers
- Payload/Body
- Source IP Address
- User Agent
- Timestamp
- Event Type
- Session ID

## API Endpoints

### Webhook Receiver

```
POST|GET|PUT|DELETE /webhook/{sessionId?}
```

**Example Usage:**
```bash
# Send a POST webhook
curl -X POST https://your-domain.com/webhook/session123 \
  -H "Content-Type: application/json" \
  -H "X-Event-Type: user.created" \
  -d '{"user_id": 123, "email": "user@example.com"}'

# Send a GET webhook
curl -X GET https://your-domain.com/webhook/session123?event=test
```


### Development Server

```bash
# Start all services (server + queue + vite)
composer run dev

# Or individually
php artisan serve
php artisan queue:work
npm run dev
```

## Configuration

### Environment Variables

Key environment variables for webhook tracking:

```env
APP_NAME="Webhook Tracker"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Theme Configuration

The application supports three theme modes:
- **Light Mode**: Manual light theme
- **Dark Mode**: Manual dark theme  
- **System Mode**: Follows system preference

## Database Schema

### Webhook Events Table

```sql
CREATE TABLE webhook_events (
    id INTEGER PRIMARY KEY,
    session_id VARCHAR NOT NULL,
    event_type VARCHAR NOT NULL,
    http_method VARCHAR(10),
    payload JSON,
    headers JSON,
    source_ip VARCHAR,
    user_agent TEXT,
    received_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## File Structure

```
├── app/
│   ├── Http/Controllers/
│   │   └── WebhookController.php
│   ├── Livewire/
│   │   └── WebhookTracker.php
│   └── Models/
│       └── WebhookEvent.php
├── resources/
│   ├── views/
│   │   ├── components/
│   │   ├── livewire/
│   │   └── welcome.blade.php
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── console.php
└── database/
    └── migrations/
```

## Features in Detail

### Real-time Updates
- Automatic polling every 5 seconds when enabled
- Visual indicators for live status
- Smooth UI updates without page refresh

### Event Management
- **New Session**: Generate fresh webhook URLs
- **Clear All**: Remove all events from current session
- **Delete Individual**: Remove specific events
- **Auto-cleanup**: Optional cleanup of old events

### Filtering & Search
- Search across event content
- Filter by event type
- Sort by timestamp
- Pagination support

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Changelog

### Version 2.0
- Complete rewrite with Laravel 11
- Modern UI with Flux components
- Real-time webhook tracking
- Mobile-responsive design
- Dark/light theme support
- Advanced filtering and search
- Session-based organization

---

