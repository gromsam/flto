import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
export default defineConfig(function (_a) {
    var mode = _a.mode;
    var env = loadEnv(mode, process.cwd(), '');
    var apiOrigin = env.VITE_API_ORIGIN || 'http://localhost:8000';
    return {
        plugins: [vue()],
        server: {
            host: true,
            port: 5173,
            proxy: {
                '/api': {
                    target: apiOrigin,
                    changeOrigin: true,
                },
            },
        },
    };
});
