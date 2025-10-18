import React, { createContext, useState, useContext, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext();

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token'));
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  const API_URL = 'http://localhost:8000/api/v1';

  // Configurer axios avec le token
  useEffect(() => {
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      loadUser();
    } else {
      setLoading(false);
    }
  }, [token]);

  // Charger les informations de l'utilisateur
  const loadUser = async () => {
    try {
      const response = await axios.get(`${API_URL}/auth/profile`);
      if (response.data.success) {
        setUser(response.data.data);
        setIsAuthenticated(true);
      }
    } catch (error) {
      console.error('Erreur lors du chargement du profil:', error);
      logout();
    } finally {
      setLoading(false);
    }
  };

  // Inscription
  const register = async (userData) => {
    try {
      const response = await axios.post(`${API_URL}/auth/register`, userData);
      if (response.data.success) {
        const { user, token } = response.data.data;
        setUser(user);
        setToken(token);
        setIsAuthenticated(true);
        localStorage.setItem('token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        return { success: true, user };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de l\'inscription',
        errors: error.response?.data?.errors
      };
    }
  };

  // Connexion
  const login = async (email, password) => {
    try {
      const response = await axios.post(`${API_URL}/auth/login`, { email, password });
      if (response.data.success) {
        const { user, token } = response.data.data;
        setUser(user);
        setToken(token);
        setIsAuthenticated(true);
        localStorage.setItem('token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        return { success: true, user };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de la connexion'
      };
    }
  };

  // Déconnexion
  const logout = async () => {
    try {
      if (token) {
        await axios.post(`${API_URL}/auth/logout`);
      }
    } catch (error) {
      console.error('Erreur lors de la déconnexion:', error);
    } finally {
      setUser(null);
      setToken(null);
      setIsAuthenticated(false);
      localStorage.removeItem('token');
      delete axios.defaults.headers.common['Authorization'];
    }
  };

  // Mettre à jour le profil
  const updateProfile = async (profileData) => {
    try {
      const response = await axios.put(`${API_URL}/auth/profile`, profileData);
      if (response.data.success) {
        setUser(response.data.data);
        return { success: true, user: response.data.data };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de la mise à jour',
        errors: error.response?.data?.errors
      };
    }
  };

  // Changer le mot de passe
  const changePassword = async (currentPassword, newPassword, newPasswordConfirmation) => {
    try {
      const response = await axios.put(`${API_URL}/auth/password`, {
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: newPasswordConfirmation
      });
      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors du changement de mot de passe',
        errors: error.response?.data?.errors
      };
    }
  };

  // Upload avatar
  const uploadAvatar = async (file) => {
    try {
      const formData = new FormData();
      formData.append('avatar', file);
      
      const response = await axios.post(`${API_URL}/auth/avatar`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      
      if (response.data.success) {
        setUser({ ...user, avatar: response.data.data.avatar });
        return { success: true, avatar: response.data.data.avatar };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de l\'upload de l\'avatar'
      };
    }
  };

  // Mot de passe oublié
  const forgotPassword = async (email) => {
    try {
      const response = await axios.post(`${API_URL}/auth/forgot-password`, { email });
      if (response.data.success) {
        return { success: true, message: response.data.message };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de la demande'
      };
    }
  };

  // Supprimer le compte
  const deleteAccount = async (password) => {
    try {
      const response = await axios.delete(`${API_URL}/auth/account`, {
        data: { password }
      });
      if (response.data.success) {
        logout();
        return { success: true, message: response.data.message };
      }
    } catch (error) {
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de la suppression du compte'
      };
    }
  };

  const value = {
    user,
    token,
    loading,
    isAuthenticated,
    register,
    login,
    logout,
    updateProfile,
    changePassword,
    uploadAvatar,
    forgotPassword,
    deleteAccount,
    loadUser
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;
