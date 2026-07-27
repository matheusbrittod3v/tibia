# OTServList Integration Checklist

This checklist keeps the server visible on OTServList and ensures the site or launcher can trust the same live status.

## DNS

- `astarot.online` must resolve to the public IP of the game server.
- If you use `www`, point it to the same origin with a `CNAME` or an `A` record.
- Avoid private or internal IPs in public listings.

## Ports

- Open the login port used by the client: `7171/tcp` by default.
- Open the game port used by the client: `7172/tcp` by default.
- Expose the status port that matches `statusProtocolPort`.
- The default `statusProtocolPort` is `7173`, so OTServList can query a
  dedicated status listener instead of sharing the login port.
- Keep the firewall and NAT mapping aligned with the chosen value.

## Server config

- `ip` should match the public hostname or public IP used by players and listings.
- `serverName` should be the public server brand.
- `serverMotd` should be a real message, not a placeholder.
- `ownerName` should match the public owner label shown on the listing.
- `ownerEmail` should be a reachable contact address.
- `url` should point to the public website.
- `location` should reflect the real hosting region.

## Validation

- Confirm the server is reachable from outside the VPS on the configured ports.
- Ensure the status protocol returns the real online player count and record.
- Do not fake online counts or uptime. OTServList can ban servers that report false data.

## Re-adding a server

- If the listing already exists and you need to force a re-register, set the MOTD to:

```text
otservlist.org-delete
```

- Keep the server online while doing that so the deletion request can be processed.

## Site endpoint

- Public JSON status endpoint:
  - `/status.json`
  - `/api/v1/status`
- Use it for badges, landing pages, or external monitors.
