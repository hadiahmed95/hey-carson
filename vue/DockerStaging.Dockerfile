FROM node:lts-alpine as build-app

WORKDIR /vue
COPY ./package*.json ./

COPY ./ ./

RUN npm install
RUN npm run staging

FROM nginx:stable-alpine as production-app
COPY --from=build-app /vue/dist /usr/share/nginx/html
COPY --from=build-app /vue/nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]