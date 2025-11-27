# base image
FROM node:14.18.0-alpine

# set working directory
WORKDIR /app

# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# install and cache app dependencies
COPY ./frontend/package.json /app/package.json
RUN yarn cache clean
RUN yarn --ignore-engines
RUN yarn global add @vue/cli --ignore-engines

# start app
CMD ["yarn", "serve"]
