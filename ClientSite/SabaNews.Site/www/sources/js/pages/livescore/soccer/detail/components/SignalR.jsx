import React, { Component } from 'react';
import * as signalR from '@microsoft/signalr';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class SignalR extends Component {
    constructor(props) {
        super(props);

        this.connection = null;
    }

    componentDidMount() {
        const protocol = new signalR.JsonHubProtocol();
        const transport = signalR.HttpTransportType.WebSockets;
        const options = {
            transport,
            logMessageContent: false,
            skipNegotiation: true,
            logger: signalR.LogLevel.None
        };

        this.connection = new signalR.HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(protocol)
            .withAutomaticReconnect()
            .build();

        this.connection.on('UpdateMatchDetail', (data) => {
            console.log('UpdateMatchDetail');
            this.props.setMatch(data);
        });

        this.connection.onclose(async () => {
            await this.start();
        });

        let lockResolver;
        if (navigator && navigator.locks && navigator.locks.request) {
            const promise = new Promise(res => {
                lockResolver = res;
            });

            navigator.locks.request('livescore_lock_navigator', { mode: 'shared' }, () => {
                return promise;
            });
        }

        this.start();
    }

    componentWillUnmount() {
        this.connection.invoke('LeaveSoccerGroup', window.selectedMatchId)
            .then(() => console.log(`leave group ${window.selectedMatchId}`))
            .catch(err => console.error(err.toString()));
        this.connection.stop();
    }

    start = () => {
        try {
            this.connection.start().then(() => {
                console.log("connect successful");
                this.connection.invoke('JoinSoccerGroup', window.selectedMatchId)
                    .then(() => console.log(`join group ${window.selectedMatchId}`))
                    .catch(err => console.error(err.toString()));
            }).catch(err => console.error(err.toString()));
        } catch (err) {
            console.log(err);
            setTimeout(() => {
                this.getMatchDetail();
                this.start();
            }, 60 * 1000);
        }
    };

    render() {
        return <span />;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(SignalR);