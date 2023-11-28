const express = require("express");

const userRoutes = require("./userRoutes");
const roleRoutes = require("./roleRoutes");
const noteRoutes = require("./noteRoutes");
const passwordRoutes = require("./passwordRoutes");

const router = express.Router();

router.use(userRoutes);
router.use(roleRoutes);
router.use(noteRoutes);
router.use(passwordRoutes);

module.exports = router;