import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import soccerMatchDetailSlice from './soccerMatchDetailSlice';
import AppContainer from './components/AppContainer';

const store = configureStore({
    reducer: soccerMatchDetailSlice
});

ReactDOM.render(
    <Provider store={store}>
        <AppContainer />
    </Provider>,
    document.getElementById('app-container'));