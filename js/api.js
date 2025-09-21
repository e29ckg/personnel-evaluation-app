import axios from 'axios';
import { getAccessToken, getRefreshToken, setTokens, clearTokens } from './auth.js';

const api = axios.create({
  baseURL: 'http://localhost/personnel-evaluation-app/api',
  headers: { 'Content-Type': 'application/json' }
});

// ✅ Interceptor: ใส่ access token ก่อนส่ง request
api.interceptors.request.use(config => {
  const token = getAccessToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// ✅ Interceptor: ถ้า token หมดอายุ → auto-refresh
api.interceptors.response.use(
  res => res,
  async err => {
    const originalRequest = err.config;
    if (err.response?.status === 401 && !originalRequest._retry && getRefreshToken()) {
      originalRequest._retry = true;
      try {
        const res = await axios.post(`${api.defaults.baseURL}/refresh_token.php`, {
          refresh_token: getRefreshToken()
        });
        setTokens(res.data.access_token, res.data.refresh_token);
        originalRequest.headers.Authorization = `Bearer ${res.data.access_token}`;
        return api(originalRequest); // retry
      } catch {
        clearTokens();
        window.location.href = 'login.php';
      }
    }
    return Promise.reject(err);
  }
);

export default api;