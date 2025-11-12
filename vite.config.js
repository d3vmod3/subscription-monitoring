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
        // host: "0.0.0.0", // allow all network interfaces (including ZeroTier)
        // port: 5173, // optional, you can change this if needed
        // hmr: {
        //     host: "10.104.185.99", // e.g. 10.147.17.45
        // },
    },
});
