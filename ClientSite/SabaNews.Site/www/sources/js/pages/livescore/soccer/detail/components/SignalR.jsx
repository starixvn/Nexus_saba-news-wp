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
        const options = {
            logMessageContent: false,
            logger: signalR.LogLevel.None
        };

        this.connection = new signalR.HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(protocol)
            .withAutomaticReconnect()
            .build();

        this.connection.on('UpdateMatchDetail', (data) => {
            console.log('UpdateMatchDetail');
            console.log(data);
            //this.props.setMatch(data);
        });

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
            setTimeout(start, 5000);
        }
    };

    render() {
        return null;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(SignalR);