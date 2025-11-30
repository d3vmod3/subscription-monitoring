import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), "");

    return {
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.js"],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            cors: true,
            host: env.VITE_SERVER_HOST,
            port: env.VITE_SERVER_PORT,
            hmr: {
                host: env.VITE_SERVER_HMR_HOST,
            },
        },
    };
});
