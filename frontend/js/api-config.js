/**
 * API Configuration for Local Backend
 * This file configures the frontend to use the local recreated backend
 */

// API Configuration
window.API_CONFIG = {
  BASE_URL: "./",
  ENDPOINTS: {
    PORTFOLIO: "./api.php",
    UPLOAD: "./api.php",
    ADMIN: "./admin.php",
  },
  HEADERS: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
};

// Enhanced fetch wrapper for API calls
window.apiCall = async function (endpoint, options = {}) {
  const url = window.API_CONFIG.ENDPOINTS[endpoint] || endpoint;
  const config = {
    headers: window.API_CONFIG.HEADERS,
    ...options,
  };

  try {
    const response = await fetch(url, config);
    if (!response.ok) {
      throw new Error(
        `API call failed: ${response.status} ${response.statusText}`
      );
    }
    return await response.json();
  } catch (error) {
    console.error("API call error:", error);
    throw error;
  }
};

// Portfolio data loader
window.loadPortfolioData = async function () {
  try {
    return await window.apiCall("PORTFOLIO");
  } catch (error) {
    console.error("Failed to load portfolio data:", error);
    // Return fallback data or show error
    return null;
  }
};

console.log("API Configuration loaded - using local backend");
