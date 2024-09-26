FROM library/php:7.1.31-alpine

RUN apk add --update git zip && \
    adduser --no-create-home -S --disabled-password --shell /bin/sh php

COPY . /src

WORKDIR /src

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN php composer.phar install && \
    cp .env.example .env

USER php

CMD ["php", "artisan", "serve"]
