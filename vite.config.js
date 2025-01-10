import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.jsx', // ตรวจสอบว่าไฟล์นี้รวมอยู่ใน input
                'resources/js/Pages/Employee/Index.jsx' // ตรวจสอบว่าไฟล์นี้รวมอยู่ใน input
            ],
            refresh: true,
        }),
        react(),
    ],
});
