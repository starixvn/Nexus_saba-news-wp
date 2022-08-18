import React, { Component } from 'react';
import { JsonHubProtocol, HubConnectionBuilder, HttpTransportType, LogLevel } from '@microsoft/signalr';
import { connect } from 'react-redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';
import { bindActionCreators } from 'redux';

export class SignalR extends Component {
    constructor(props) {
        super(props);
        this.connection = null;
    }

    componentDidMount() {
        const options = {
            transport: HttpTransportType.WebSockets,
            logMessageContent: false,
            skipNegotiation: true,
            logger: LogLevel.None
        };

        this.connection = new HubConnectionBuilder()
            .withUrl('/livescore/hubs', options)
            .withHubProtocol(new JsonHubProtocol())
            .build();

        this.connection.onclose(async () => {
            await this.start();
        });

        this.connection.on('UpdateLiveMatches', matches => {
            this.props.setLiveMatches(matches);
        });

        Site.lockNavigator();

        this.start();
    }

    start = async () => {
        try {
            await this.connection.start().then(() => {
                console.log('SignalR connected!!!'); //NOSONAR
            });
        } catch (err) {
            setTimeout(() => {
                this.props.getMatches();
                this.start();
            }, 60 * 1000);

            console.log(err); //NOSONAR
        }
    };

    componentWillUnmount() {
        this.connection.stop();
    }

    render() {
        return <span />;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(SignalR);