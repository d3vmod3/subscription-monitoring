import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        host: process.env.VITE_SERVER_HOST, // allow all network interfaces (including ZeroTier)
        port: process.env.VITE_SERVER_PORT, // optional, you can change this if needed
        hmr: {
            host: process.env.VITE_SERVER_HMR_HOST, // e.g. 10.147.17.45
        },
    },
});
