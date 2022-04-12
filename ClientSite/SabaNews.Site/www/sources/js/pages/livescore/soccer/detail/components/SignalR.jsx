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
        const transport = signalR.HttpTransportType.LongPolling;
        const options = {
            transport,
            logMessageContent: false,
            logger: signalR.LogLevel.None
        };

        this.connection = new signalR.HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(protocol)
            .withAutomaticReconnect()
            .build();

        // this.connection.on('UpdateMatchDetail', (data) => {
        //     console.log('UpdateMatchDetail');
        //     console.log(data);
        //     this.props.setSoccerMatch(data);
        // });

        this.connection.on('UpdateMatchDetail', (data) => {
            console.log('UpdateMatchDetail');
            this.props.setMatch(data);
        });


        this.connection.start().then(() => {
            this.connection.invoke('JoinSoccerGroup', window.selectedMatchId)
                .then(() => console.log(`join group ${window.selectedMatchId}`))
                .catch(err => console.error(err.toString()));
        }).catch(err => console.error(err.toString()));
    }

    componentWillUnmount() {
        this.connection.invoke('LeaveSoccerGroup', window.selectedMatchId)
            .then(() => console.log(`leave group ${window.selectedMatchId}`))
            .catch(err => console.error(err.toString()));
        this.connection.stop();
    }

    render() {
        return null;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(SignalR);