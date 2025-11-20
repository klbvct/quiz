import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import Register from '../src/pages/Register';
import { MemoryRouter } from 'react-router-dom';

describe('Registration Component', () => {
  test('renders registration form', () => {
    render(
      <MemoryRouter>
        <Register />
      </MemoryRouter>
    );

    const usernameInput = screen.getByPlaceholderText(/username/i);
    const passwordInput = screen.getByPlaceholderText(/password/i);
    const submitButton = screen.getByRole('button', { name: /register/i });

    expect(usernameInput).toBeInTheDocument();
    expect(passwordInput).toBeInTheDocument();
    expect(submitButton).toBeInTheDocument();
  });

  test('allows user to register', () => {
    render(
      <MemoryRouter>
        <Register />
      </MemoryRouter>
    );

    fireEvent.change(screen.getByPlaceholderText(/username/i), { target: { value: 'testuser' } });
    fireEvent.change(screen.getByPlaceholderText(/password/i), { target: { value: 'password123' } });
    fireEvent.click(screen.getByRole('button', { name: /register/i }));

    // Add assertions to check if registration was successful
    // This could involve checking for a success message or redirect
  });
});