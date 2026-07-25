ARG CANARY_IMAGE=ghcr.io/opentibiabr/canary:latest
FROM ${CANARY_IMAGE}

USER root

RUN if command -v apt-get >/dev/null 2>&1; then \
        apt-get update \
        && apt-get install -y --no-install-recommends \
            bash \
            ca-certificates \
            default-mysql-client \
            redis-tools \
        && rm -rf /var/lib/apt/lists/*; \
    elif command -v apk >/dev/null 2>&1; then \
        apk add --no-cache \
            bash \
            ca-certificates \
            mariadb-client \
            redis; \
    else \
        echo "Unsupported base image package manager." >&2; \
        exit 1; \
    fi

WORKDIR /canary

RUN if command -v addgroup >/dev/null 2>&1 && command -v adduser >/dev/null 2>&1; then \
        addgroup -S canary 2>/dev/null || addgroup --system canary; \
        adduser -S -D -H -G canary canary 2>/dev/null || adduser --system --no-create-home --ingroup canary canary; \
    elif command -v groupadd >/dev/null 2>&1 && command -v useradd >/dev/null 2>&1; then \
        groupadd --system canary; \
        useradd --system --no-create-home --gid canary canary; \
    else \
        echo "No supported user creation command found." >&2; \
        exit 1; \
    fi

COPY entrypoint.sh /usr/local/bin/canary-entrypoint.sh
COPY schema.sql /canary/schema.sql
COPY data/XML/vocations.xml /canary/data/XML/vocations.xml.dist

RUN chmod +x /usr/local/bin/canary-entrypoint.sh \
    && chown -R canary:canary /canary /usr/local/bin/canary-entrypoint.sh

USER canary

ENTRYPOINT ["/bin/bash", "/usr/local/bin/canary-entrypoint.sh"]
CMD ["/usr/bin/canary"]
