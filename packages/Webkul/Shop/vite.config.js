import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig(({ mode }) => {
    const envDir = "../../../";

    Object.assign(process.env, loadEnv(mode, envDir));

    return {
        build: {
            emptyOutDir: true,
            minify: "esbuild",
            cssCodeSplit: true,
            sourcemap: false,
            reportCompressedSize: false,
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            if (id.includes('vue')) return 'vue';
                            if (id.includes('vee-validate')) return 'vee-validate';
                            if (id.includes('axios')) return 'axios';
                            return 'vendor';
                        }
                    },
                    entryFileNames: 'js/[name]-[hash].js',
                    chunkFileNames: 'js/[name]-[hash].js',
                    assetFileNames: (assetInfo) => {
                        const info = assetInfo.name.split('.');
                        const ext = info[info.length - 1];
                        if (/png|jpe?g|gif|svg/.test(ext)) {
                            return 'images/[name]-[hash][extname]';
                        } else if (/woff|woff2|eot|ttf|otf/.test(ext)) {
                            return 'fonts/[name]-[hash][extname]';
                        }
                        return 'css/[name]-[hash][extname]';
                    }
                }
            },
            // Optimasi untuk chunk size
            chunkSizeWarningLimit: 500,
            target: 'esnext',
        },

        envDir,

        resolve: {
            alias: {
                '@': '/src/Resources/assets/js',
                '@css': '/src/Resources/assets/css',
            },
            extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue']
        },

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5173,
            cors: true,
            middlewareMode: false,
            hmr: {
                host: process.env.VITE_HMR_HOST || 'localhost',
                port: process.env.VITE_HMR_PORT || 5173,
                protocol: process.env.VITE_HMR_PROTOCOL || 'ws',
            },
        },

        plugins: [
            vue(),

            laravel({
                hotFile: "../../../public/shop-default-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/shop/default/build",
                input: [
                    "src/Resources/assets/css/app.css",
                    "src/Resources/assets/js/app.js",
                ],
                refresh: true,
                preload: false,
            }),
        ],

        experimental: {
            renderBuiltUrl(filename, { hostId, hostType, type }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },
    };
});
