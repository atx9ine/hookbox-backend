<div align="center">

<img src="./assets/hookbox_logo.png" alt="HookBox" width="420" />

**High-performance webhook inspection and relay platform.**

Capture, inspect, and forward HTTP requests with real-time monitoring and delivery analytics.

[Features](#features) · [How It Works](#how-it-works) · [Getting Started](#getting-started) · [Usage](#usage) · [Contributing](#contributing)

</div>

---

## Overview

HookBox gives you a unique webhook URL in seconds. Point any service at it, and HookBox captures every incoming request so you can inspect it in detail, then relay it to the destination of your choice, with full visibility into whether delivery succeeded.

Use it to debug third-party integrations, test payment or messaging callbacks, or fan webhooks out to other services without writing glue code.

> [!NOTE]
> **HookBox is under active development.** Features and screenshots shown here may change, and some functionality is not yet available.

## Features

- **Instant webhook URLs**: generate unique endpoints on demand
- **Request inspection**: view method, headers, query params, and body for every captured request
- **Relay and forwarding**: send captured requests to one or more destination URLs
- **Real-time monitoring**: watch requests arrive live as they happen
- **Delivery analytics**: track success, failure, status codes, and response times
- **Replay**: re-send any captured request to a destination

## How It Works

```
┌──────────────┐     ┌──────────────────────┐     ┌──────────────────┐
│ Third-party  │────▶│       HookBox        │────▶│   Your service   │
│   service    │     │  capture · inspect   │     │   (destination)  │
└──────────────┘     │  relay · analytics   │     └──────────────────┘
                     └──────────────────────┘
```

1. **Generate** a unique webhook URL.
2. **Receive** requests sent to that URL.
3. **Inspect** the payload, headers, and metadata in real time.
4. **Relay** the request to your destination and track the delivery result.

## Getting Started

### Prerequisites

- Add your runtime and tooling requirements here (e.g. PHP 8.3+, Node.js 20+, a database)

### Installation

```bash
# Clone the repository
git clone https://github.com/<your-username>/hookbox.git
cd hookbox

# Install dependencies
# <add your install commands>

# Configure environment
cp .env.example .env

# Run the app
# <add your start command>
```

### Configuration

| Variable | Description |
| --- | --- |
| `APP_URL` | Public base URL used to build webhook endpoints |
| `DB_*` | Database connection settings |

Update this table to match your `.env.example`.

## Usage

### 1. Create an endpoint

Create a new webhook from the dashboard. You will receive a URL like:

```
https://your-domain.com/hooks/abc123xyz
```

### 2. Send a test request

```bash
curl -X POST https://your-domain.com/hooks/abc123xyz \
  -H "Content-Type: application/json" \
  -d '{"event": "payment.success", "amount": 5000}'
```

### 3. Inspect and relay

Open the request in the dashboard to view its full details, then set a destination URL to forward it. Delivery status and response time are recorded for every attempt.

## Screenshots

### Dashboard

An overview of your webhook traffic: total endpoints, requests received, success and failure rates, and a live feed of recent requests with method, endpoint, status, and source.

![HookBox dashboard](./assets/dashboard.png)

> Preview of the dashboard design. The UI is still being built and may change.

## Roadmap

- [ ] Signature verification for popular providers
- [ ] Retry policies with backoff
- [ ] Multiple destinations per endpoint
- [ ] Team workspaces

## Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m "Add your feature"`
4. Push the branch: `git push origin feature/your-feature`
5. Open a pull request

## License

Distributed under the MIT License. See [`LICENSE`](./LICENSE) for details.
