import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import leagueDetailSlice from './leagueDetailSlice';
import AppContainer from './components/AppContainer';
import Site from '@jsroot/common/site';

const store = configureStore({
    reducer: leagueDetailSlice
});

const params = window.getMatchByStageParams;
const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet-giai-dau/${params.date}/${params.leagueId}/${params.seasonId}`);

ReactDOM.render(
    <Provider store={store}>
        <BrowserRouter>
            <Routes>
                <Route path={url} element={<AppContainer />} />
                <Route path={`${url}/:selectedTab`} element={<AppContainer />} />
            </Routes>
        </BrowserRouter>
    </Provider>,
    document.getElementById('app-container'));