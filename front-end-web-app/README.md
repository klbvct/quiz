# Front-End Web Application

This project is a front-end web application built using React and TypeScript. It features user authentication, a user dashboard, an admin dashboard, and a test page for users after registration.

## Project Structure

```
front-end-web-app
├── public
│   └── index.html          # Main HTML file serving as the entry point
├── src
│   ├── main.tsx           # Entry point for the React application
│   ├── App.tsx            # Main application component with routing
│   ├── pages               # Contains all page components
│   │   ├── Home.tsx       # Main page of the application
│   │   ├── Login.tsx      # User login page
│   │   ├── Register.tsx    # User registration page
│   │   ├── UserDashboard.tsx # Dashboard for logged-in users
│   │   ├── TestPage.tsx   # Test page for users after registration
│   │   ├── AdminLogin.tsx  # Admin login page
│   │   └── AdminDashboard.tsx # Dashboard for admins
│   ├── components          # Contains reusable components
│   │   ├── Navbar.tsx     # Navigation bar component
│   │   ├── LoginForm.tsx  # Component for user login form
│   │   └── ProtectedRoute.tsx # Component for protecting routes
│   ├── services            # Contains service functions
│   │   └── auth.ts        # Authentication functions
│   ├── hooks               # Custom hooks
│   │   └── useAuth.ts     # Hook for authentication state
│   ├── store               # Redux store configuration
│   │   └── index.ts       # Store setup
│   ├── styles              # CSS styles
│   │   └── globals.css     # Global styles
│   └── types               # TypeScript types
│       └── index.ts       # Type definitions
├── tests                   # Test files
│   └── registration.test.tsx # Tests for registration functionality
├── package.json            # NPM configuration file
├── tsconfig.json           # TypeScript configuration file
├── vite.config.ts          # Vite configuration file
└── README.md               # Project documentation
```

## Features

- User registration and login
- User dashboard displaying user-specific information
- Admin login and dashboard for managing users
- Test page for users after registration
- Protected routes to restrict access based on authentication status

## Getting Started

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd front-end-web-app
   ```

3. Install dependencies:
   ```
   npm install
   ```

4. Start the development server:
   ```
   npm run dev
   ```

5. Open your browser and go to `http://localhost:3000` to view the application.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License.