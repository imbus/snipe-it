/**
 * Advanced Search - Filters Bundle
 * Contains filter form management and all filter input types
 */

import './filterFormManager.js';

// Import all filter input components
const filterInputContext = require.context('./filterInputs', false, /\.js$/);
filterInputContext.keys().forEach(filterInputContext);
