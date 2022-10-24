import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import Grid from './Grid';
import { WorldCup } from '@jsroot/common/constants';
import { bool } from 'prop-types';

export default class Standing extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleStanding = (matches, votes) => {
        let users = {
            userName: null,
            userId: null,
            icon: null,
            points: 0,
            createdDate: null,
            updatedDate: null
        };
        let standings = [];
        votes.map(index => {
            if(standings.length === 0){
                standings.push({
                    userName: index.userName,
                    icon: index.icon,
                    userId: index.userId,
                    points: 0,
                    createdDate: index.createdDate,
                    updatedDate: index.updatedDate
                })
            }
            else if(this.checkUser(standings, index.userId) === false){
                standings.push({
                    userName: index.userName,
                    icon: index.icon,
                    userId: index.userId,
                    points: 0,
                    createdDate: index.createdDate,
                    updatedDate: index.updatedDate
                })
            }
        })
        return standings;
    }

    checkUser = (standings, userId) => {
        standings.map(index => {
            if(index.userId === userId){
                return true;
            }
        })
        return false;
    } 

    render() {
        const { matches, votes, search } = this.props;
        const standinglist = this.handleStanding(matches, votes);
        return (
            <div>
                hello
            </div>
        );
    }
}
