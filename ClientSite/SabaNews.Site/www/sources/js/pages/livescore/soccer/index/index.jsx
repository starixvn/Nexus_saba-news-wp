import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import soccerMatchSlice from './soccerMatchSlice';
import AppContainer from './components/AppContainer';

const store = configureStore({
    reducer: soccerMatchSlice
});

ReactDOM.render(
    <Provider store={store}>
        <AppContainer />
    </Provider>,
    document.getElementById('app-container'));