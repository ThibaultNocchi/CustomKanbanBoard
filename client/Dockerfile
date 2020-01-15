FROM node:alpine AS debug

RUN npm install -g @vue/cli
WORKDIR /project

COPY package*.json ./
RUN npm install

CMD npm run serve

#
FROM debug AS build

WORKDIR /project
COPY . .
RUN npm run build

#
FROM nginx:alpine AS prod

COPY --from=build /project/dist /usr/share/nginx/html
