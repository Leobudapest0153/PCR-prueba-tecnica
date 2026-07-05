FROM node:20-alpine

WORKDIR /app

COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 5173

ENTRYPOINT ["entrypoint.sh"]
CMD ["npm", "run", "dev", "--", "--host"]
