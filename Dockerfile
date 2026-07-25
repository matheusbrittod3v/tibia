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

RUN if command -v apk >/dev/null 2>&1; then \
        addgroup -S canary; \
        adduser -S -D -H -G canary canary; \
    elif command -v apt-get >/dev/null 2>&1; then \
        getent group canary >/dev/null 2>&1 || groupadd --system canary; \
        id -u canary >/dev/null 2>&1 || useradd --system --no-create-home --gid canary --shell /usr/sbin/nologin canary; \
    else \
        echo "No supported user creation command found." >&2; \
        exit 1; \
    fi \
    && getent group canary >/dev/null 2>&1 \
    && getent passwd canary >/dev/null 2>&1

COPY entrypoint.sh /usr/local/bin/canary-entrypoint.sh
COPY schema.sql /canary/schema.sql
COPY data/XML/vocations.xml /canary/data/XML/vocations.xml.dist

RUN chmod +x /usr/local/bin/canary-entrypoint.sh \
    && chown -R canary:canary /canary /usr/local/bin/canary-entrypoint.sh

USER canary

ENTRYPOINT ["/bin/bash", "/usr/local/bin/canary-entrypoint.sh"]
CMD ["/usr/bin/canary"]
