import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import UserDashboard from './pages/UserDashboard';
import TestPage from './pages/TestPage';
import AdminLogin from './pages/AdminLogin';
import AdminDashboard from './pages/AdminDashboard';
import Navbar from './components/Navbar';
import ProtectedRoute from './components/ProtectedRoute';

const App = () => {
  return (
    <Router>
      <Navbar />
      <Switch>
        <Route path="/" exact component={Home} />
        <Route path="/login" component={Login} />
        <Route path="/register" component={Register} />
        <ProtectedRoute path="/user-dashboard" component={UserDashboard} />
        <ProtectedRoute path="/test" component={TestPage} />
        <Route path="/admin-login" component={AdminLogin} />
        <ProtectedRoute path="/admin-dashboard" component={AdminDashboard} />
      </Switch>
    </Router>
  );
};

export default App;