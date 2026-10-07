/**
 * Client service for interacting with the AI Creator Marketplace SQLite Backend.
 */

const API_BASE = '/api'

const getHeaders = () => {
  const token = localStorage.getItem('token')
  const headers = { 'Content-Type': 'application/json' }
  if (token) {
    headers['Authorization'] = `Bearer ${token}`
  }
  return headers
}

async function request(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`
  const config = {
    ...options,
    headers: {
      ...getHeaders(),
      ...(options.headers || {}),
    },
  }

  if (config.body && typeof config.body === 'object' && !(config.body instanceof FormData)) {
    config.body = JSON.stringify(config.body)
  }

  const response = await fetch(url, config)
  const data = await response.json().catch(() => null)

  if (!response.ok) {
    const errorMsg = data?.detail || data?.message || `Request failed with status ${response.status}`
    throw new Error(errorMsg)
  }

  return data
}

export const api = {
  // Authentication
  auth: {
    signup: (payload) => request('/auth/signup', { method: 'POST', body: payload }),
    login: async (payload) => {
      const data = await request('/auth/login', { method: 'POST', body: payload })
      if (data?.access_token) {
        localStorage.setItem('token', data.access_token)
        localStorage.setItem('user', JSON.stringify(data.user))
      }
      return data
    },
    me: () => request('/auth/me'),
    logout: () => {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    },
  },

  // Creators
  creators: {
    list: (params = {}) => {
      const searchParams = new URLSearchParams()
      if (params.search) searchParams.set('search', params.search)
      if (params.filter && params.filter !== 'All') searchParams.set('filter', params.filter)
      const query = searchParams.toString() ? `?${searchParams.toString()}` : ''
      return request(`/creators${query}`)
    },
    getById: (id) => request(`/creators/${id}`),
    getMyProfile: () => request('/creators/me'),
    updateMyProfile: (payload) => request('/creators/me', { method: 'PUT', body: payload }),
  },

  // Portfolios
  portfolios: {
    list: (params = {}) => {
      const searchParams = new URLSearchParams()
      if (params.creatorId) searchParams.set('creator_id', params.creatorId)
      if (params.contentType) searchParams.set('contentType', params.contentType)
      const query = searchParams.toString() ? `?${searchParams.toString()}` : ''
      return request(`/portfolios${query}`)
    },
    getById: (id) => request(`/portfolios/${id}`),
    create: (payload) => request('/portfolios', { method: 'POST', body: payload }),
    update: (id, payload) => request(`/portfolios/${id}`, { method: 'PUT', body: payload }),
    delete: (id) => request(`/portfolios/${id}`, { method: 'DELETE' }),
  },

  // Campaign Briefs
  briefs: {
    list: (params = {}) => {
      const searchParams = new URLSearchParams()
      if (params.status) searchParams.set('status', params.status)
      if (params.contentType) searchParams.set('contentType', params.contentType)
      const query = searchParams.toString() ? `?${searchParams.toString()}` : ''
      return request(`/briefs${query}`)
    },
    getById: (id) => request(`/briefs/${id}`),
    create: (payload) => request('/briefs', { method: 'POST', body: payload }),
    update: (id, payload) => request(`/briefs/${id}`, { method: 'PUT', body: payload }),
    delete: (id) => request(`/briefs/${id}`, { method: 'DELETE' }),
    apply: (briefId, payload) => request(`/briefs/${briefId}/apply`, { method: 'POST', body: payload }),
  },

  // Dashboard Stats
  dashboard: {
    creator: () => request('/dashboard/creator'),
    brand: () => request('/dashboard/brand'),
  },

  // System Health
  health: () => request('/health'),
}

export default api
