FROM node:10.16.3-alpine as build

RUN mkdir -p /app
WORKDIR /app

ENV PATH /app/node_modules/.bin:$PATH
#ENV CHOKIDAR_USEPOLLING=true
#ENV SKIP_PREFLIGHT_CHECK=true

RUN echo "Install NPM dependencies........."
COPY ./bizwhois-app/package*.json ./
RUN yarn

RUN echo "Front-End Ready!"

# defined in package.json
CMD [ "yarn", "run", "start" ]
