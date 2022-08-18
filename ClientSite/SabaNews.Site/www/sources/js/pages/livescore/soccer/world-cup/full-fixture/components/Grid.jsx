import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Stage from './Stage';
import MobileStage from './MobileStage';
import { MobileMaxWidth } from '@jsroot/common/constants';
export class Grid extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        this.props.getMatches();
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    };


    onSetStageId = key => {
        this.props.setSelectedStageId(key);
    };

    render() {
        const { selectedStageId, stages } = this.props;
        const { isMobile } = this.state;
        return (
            <div className="livescore-soccer">
                <div className="livescore-soccer-main" >
                    {
                        !isMobile &&
                        <Stage stages={stages} selectedStageId={selectedStageId} onSetStageId={this.onSetStageId} />
                    }
                    {
                        isMobile &&
                        <MobileStage stages={stages} selectedStageId={selectedStageId} onSetStageId={this.onSetStageId} />
                    }
                </div>
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, league, selectedStageId } = state;
    return { matches, league, selectedStageId };
};

export default connect(mapStateToProps, mapDispatchToProps)(Grid);