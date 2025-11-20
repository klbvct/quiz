# Quiz Vue App

Minimal Vue 3 + Vite app to interact with the `check-project` backend.

Quick start:

1. Open a terminal and go to the app folder:

```powershell
cd "C:\Users\user\Downloads\quiz.education-design.com.ua\vue-app"
```

2. Install dependencies and run dev server:

```powershell
npm install
npm run dev
```

3. Open the URL shown by Vite (usually `http://localhost:5173`).

Notes:
- The example POSTs to `http://localhost:3000/check` (this is the `check-project` server). If that backend runs on a different origin, enable CORS on the backend or proxy requests via Vite.
- To build for production: `npm run build` and `npm run preview` to preview the built app.
