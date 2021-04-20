FROM node:14-alpine

WORKDIR /app

COPY ./front /app

RUN yarn install --pure-lockfile --cache-folder /tmp/yarn_cache_for_docker/ --production=false --no-progress --prefer-offline \
 && yarn check --integrity --verify-tree --no-progress --non-interactive \
 && yarn generate \
 && yarn build

CMD ["yarn", "start"]
