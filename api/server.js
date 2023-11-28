import gracefulShutdown from "http-graceful-shutdown";
import app from './app';

const server = app.listen(process.env.PORT, () => {
  console.log(`⚡️[server]: Server is running at http://localhost:${process.env.PORT}`);
  // logger.info(`Server started on port: ${process.env.PORT}`);
});

gracefulShutdown(server);