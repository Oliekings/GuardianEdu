FROM node:20

# Set working directory
WORKDIR /var/www/html

# Install dependencies (package.json must exist in project root)
COPY package*.json ./
RUN ls -la .
RUN apt-get update && apt-get install -y python3 make g++ && ln -s /usr/bin/python3 /usr/bin/python
RUN npm install --silent --unsafe-perm

# Copy the rest of the application (excluding node_modules which will be mounted)
COPY . .

# Expose Vite dev server default port
EXPOSE 5173

CMD ["npm", "run", "dev"]
