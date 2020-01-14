FROM node:alpine

RUN npm install -g @vue/cli
WORKDIR /project

COPY package*.json ./
RUN npm install

CMD npm run serve
